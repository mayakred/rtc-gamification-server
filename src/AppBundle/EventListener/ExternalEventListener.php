<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 24.09.16
 * Time: 15:48.
 */
namespace AppBundle\EventListener;

use AppBundle\Event\ExternalEvent;
use AppBundle\Handler\DuelHandler;
use AppBundle\Handler\EventHandler;

class ExternalEventListener
{
    /**
     * @var DuelHandler
     */
    protected $duelHandler;

    /**
     * @var EventHandler
     */
    protected $eventHandler;

    /**
     * ExternalEventListener constructor.
     *
     * @param DuelHandler  $duelHandler
     * @param EventHandler $eventHandler
     */
    public function __construct(DuelHandler $duelHandler, EventHandler $eventHandler)
    {
        $this->duelHandler = $duelHandler;
        $this->eventHandler = $eventHandler;
    }

    public function onExternalEvent(ExternalEvent $externalEvent)
    {
        $this->duelHandler->handleExternalEvent($externalEvent->getEvent());
        $this->eventHandler->handleExternalEvent($externalEvent->getEvent());
    }
}
