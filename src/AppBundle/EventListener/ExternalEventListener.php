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

class ExternalEventListener
{
    /**
     * @var DuelHandler
     */
    protected $duelHandler;

    /**
     * ExternalEventListener constructor.
     *
     * @param DuelHandler $duelHandler
     */
    public function __construct(DuelHandler $duelHandler)
    {
        $this->duelHandler = $duelHandler;
    }

    public function onExternalEvent(ExternalEvent $externalEvent)
    {
        $this->duelHandler->handleExternalEvent($externalEvent->getEvent());
    }
}
