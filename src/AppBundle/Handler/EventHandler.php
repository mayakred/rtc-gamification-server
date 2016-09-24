<?php

namespace AppBundle\Handler;

use AppBundle\DBAL\Types\CallType;
use AppBundle\DBAL\Types\PushType;
use AppBundle\Entity\CallEvent;
use AppBundle\Entity\Event;
use AppBundle\Entity\MeetingEvent;
use AppBundle\Entity\Metric;
use AppBundle\Entity\SaleEvent;
use AppBundle\Event\ExternalEvent;
use AppBundle\Event\PushEvent;
use AppBundle\Manager\EventManager;
use AppBundle\Manager\MetricManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventHandler
{
    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var MetricManager
     */
    protected $metricManager;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * UserHandler constructor.
     *
     * @param EventManager             $eventManager
     * @param MetricManager            $metricManager
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventManager $eventManager, MetricManager $metricManager, EventDispatcherInterface $dispatcher)
    {
        $this->eventManager = $eventManager;
        $this->metricManager = $metricManager;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param Event $event
     *
     * @return Event
     */
    public function add(Event $event)
    {
        $this->eventManager->save($event);

        $this->dispatcher->dispatch(ExternalEvent::NAME, new ExternalEvent($event));

        return $event;
    }

    /**
     * @param Event $event
     */
    public function handleExternalEvent(Event $event)
    {
        /**
         * @var Metric[] $availableMetrics
         */
        $availableMetrics = $this->metricManager->findAvailableForIndividualTournaments();
        $eventMetrics = $this->convertEventToMetrics($event);
        $resultMetrics = [];
        foreach ($availableMetrics as $availableMetric) {
            foreach ($eventMetrics as $metricCode => $metricValue) {
                if ($metricCode === $availableMetric->getCode()) {
                    $resultMetrics[$metricCode] = $metricValue;
                    break;
                }
            }
        }

        $em = $this->eventManager->getEntityManager();

        $user = $event->getUser();
        $userAchievements = $user->getUserAchievements();
        foreach ($userAchievements as $userAchievement) {
            $achievement = $userAchievement->getAchievement();
            $isReachedAlready = $achievement->isReached($userAchievement);
            $oldValue = $userAchievement->getValue();
            foreach ($resultMetrics as $metricCode => $metricValue) {
                if ($achievement->getMetric()->getCode() !== $metricCode) {
                    continue;
                }
                $userAchievement->addValue($metricValue);
            }

            if (!$isReachedAlready && $oldValue < $userAchievement->getValue() && $achievement->isReached($userAchievement)) {
                $this->dispatcher->dispatch(
                    PushEvent::NAME,
                    new PushEvent(
                        '',
                        '',
                        $user,
                        PushType::EVENT_REACHED,
                        null,
                        $achievement
                    )
                );
            }

            $em->persist($userAchievement);
        }
        $em->flush();
    }

    /**
     * @param Event $event
     *
     * @return array
     */
    public function convertEventToMetrics(Event $event)
    {
        $metrics = [];
        if ($event instanceof MeetingEvent) {
            $metrics['metric_type.meets_in_units'] = 1;
        } elseif ($event instanceof SaleEvent) {
            $metrics['metric_type.sales_in_rubles'] = $event->getTotal();
            $metrics['metric_type.sales_in_rubles_percentage'] = $event->getTotal();
            $metrics['metric_type.sales_in_units'] = $event->getItems()->count();
            $metrics['metric_type.sales_in_units_percentage'] = $event->getItems()->count();
        } elseif ($event instanceof CallEvent) {
            if ($event->getCallType() === CallType::COLD) {
                $metrics['metric_type.cold_calls_in_units'] = 1;
            } elseif ($event->getCallType() === CallType::HOT) {
                $metrics['metric_type.warm_calls_in_units'] = 1;
            }
        }

        return $metrics;
    }
}
