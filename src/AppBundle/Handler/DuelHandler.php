<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 24.09.16
 * Time: 15:51.
 */
namespace AppBundle\Handler;

use AppBundle\Entity\Event;
use AppBundle\Entity\Metric;
use AppBundle\Manager\DuelManager;
use AppBundle\Manager\MetricManager;

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
     * DuelHandler constructor.
     *
     * @param DuelManager   $duelManager
     * @param MetricManager $metricManager
     * @param EventHandler  $eventHandler
     */
    public function __construct(DuelManager $duelManager, MetricManager $metricManager, EventHandler $eventHandler)
    {
        $this->duelManager = $duelManager;
        $this->metricManager = $metricManager;
        $this->eventHandler = $eventHandler;
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
}
