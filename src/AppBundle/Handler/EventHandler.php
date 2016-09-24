<?php

namespace AppBundle\Handler;

use AppBundle\DBAL\Types\CallType;
use AppBundle\Entity\CallEvent;
use AppBundle\Entity\Event;
use AppBundle\Entity\MeetingEvent;
use AppBundle\Entity\SaleEvent;
use AppBundle\Event\ExternalEvent;
use AppBundle\Manager\EventManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventHandler
{
    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * UserHandler constructor.
     *
     * @param EventManager             $eventManager
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventManager $eventManager, EventDispatcherInterface $dispatcher)
    {
        $this->eventManager = $eventManager;
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
                $metrics['metric_type.warp_calls_in_units'] = 1;
            }
        }

        return $metrics;
    }
}
