<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 24.09.16
 * Time: 15:51.
 */
namespace AppBundle\Handler;

use AppBundle\DBAL\Types\DuelStatusType;
use AppBundle\DBAL\Types\PushType;
use AppBundle\Entity\Event;
use AppBundle\Entity\Metric;
use AppBundle\Event\DuelEvent;
use AppBundle\Event\DuelWonEvent;
use AppBundle\Event\PushEvent;
use AppBundle\Manager\DuelManager;
use AppBundle\Manager\MetricManager;
use AppBundle\Manager\UserManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DuelHandler
{
    /**
     * @var DuelManager
     */
    protected $duelManager;

    /**
     * @var MetricManager
     */
    protected $metricManager;

    /**
     * @var EventHandler
     */
    protected $eventHandler;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * DuelHandler constructor.
     *
     * @param DuelManager              $duelManager
     * @param MetricManager            $metricManager
     * @param EventHandler             $eventHandler
     * @param EventDispatcherInterface $dispatcher
     * @param UserManager              $userManager
     */
    public function __construct(
        DuelManager $duelManager, MetricManager $metricManager,
        EventHandler $eventHandler, EventDispatcherInterface $dispatcher,
        UserManager $userManager
    ) {
        $this->duelManager = $duelManager;
        $this->metricManager = $metricManager;
        $this->eventHandler = $eventHandler;
        $this->dispatcher = $dispatcher;
        $this->userManager = $userManager;
    }

    /**
     * @param Event $event
     */
    public function handleExternalEvent(Event $event)
    {
        /**
         * @var Metric[] $availableMetrics
         */
        $availableMetrics = $this->metricManager->findBy(['availableForDuel' => true]);
        $eventMetrics = $this->eventHandler->convertEventToMetrics($event);
        $resultMetrics = [];
        foreach ($availableMetrics as $availableMetric) {
            foreach ($eventMetrics as $metricCode => $metricValue) {
                if ($metricCode === $availableMetric->getCode()) {
                    $resultMetrics[$metricCode] = $metricValue;
                    break;
                }
            }
        }
        $user = $event->getUser();
        $duels = $this->duelManager->findActiveDuelsRelatedToUser($event->getUser());
        foreach ($duels as $duel) {
            foreach ($resultMetrics as $metricCode => $metricValue) {
                if ($duel->getMetric()->getCode() !== $metricCode) {
                    continue;
                }
                if ($duel->getVictim()->getId() === $user->getId()) {
                    $duel->setVictimValue($duel->getVictimValue() + $metricValue);
                } elseif ($duel->getInitiator()->getId() === $user->getId()) {
                    $duel->setInitiatorValue($duel->getInitiatorValue() + $metricValue);
                }
            }
            $this->duelManager->save($duel, false);
        }
        $this->duelManager->getEntityManager()->flush();
    }

    public function finishDuels()
    {
        $pushEvents = [];
        $wonEvents = [];
        $winModifier = 10 * 1024 * 1024;
        $duels = $this->duelManager->findFinishedDuels();
        foreach ($duels as $duel) {
            $winner = null;
            $looser = null;
            $vValue = $duel->getVictimValue();
            $iValue = $duel->getInitiatorValue();
            if ($vValue > $iValue) {
                $duel->setStatus(DuelStatusType::VICTIM_WIN);
                $duel->getVictim()->setRating($duel->getVictim()->getRating() + $winModifier);
                $pushEvents[] = new PushEvent('', '', $duel->getVictim(), PushType::DUEL_WON, $duel);
                $pushEvents[] = new PushEvent('', '', $duel->getInitiator(), PushType::DUEL_DEFEATED, $duel);
                $wonEvents[] = new DuelWonEvent($duel);
            } elseif ($vValue < $iValue) {
                $duel->setStatus(DuelStatusType::INITIATOR_WIN);
                $duel->getInitiator()->setRating($duel->getInitiator()->getRating() + $winModifier);
                $pushEvents[] = new PushEvent('', '', $duel->getInitiator(), PushType::DUEL_WON, $duel);
                $pushEvents[] = new PushEvent('', '', $duel->getVictim(), PushType::DUEL_DEFEATED, $duel);
                $wonEvents[] = new DuelWonEvent($duel);
            } else {
                $duel->setStatus(DuelStatusType::DRAW);
                $pushEvents[] = new PushEvent('', '', $duel->getVictim(), PushType::DUEL_DRAW, $duel);
                $pushEvents[] = new PushEvent('', '', $duel->getInitiator(), PushType::DUEL_DRAW, $duel);
            }
        }
        $this->duelManager->getEntityManager()->flush();

        foreach ($pushEvents as $pushEvent) {
            $this->dispatcher->dispatch(PushEvent::NAME, $pushEvent);
        }
        foreach ($wonEvents as $wonEvent) {
            $this->dispatcher->dispatch(DuelEvent::NAME, $wonEvent);
        }

        $this->userManager->recalcUserPosition();
    }
}
