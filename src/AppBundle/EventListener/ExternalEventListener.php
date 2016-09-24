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
use AppBundle\Handler\TournamentHandler;

class ExternalEventListener
{
    /**
     * @var DuelHandler
     */
    protected $duelHandler;

    /**
     * @var TournamentHandler
     */
    protected $tournamentHandler;

    /**
     * ExternalEventListener constructor.
     *
     * @param DuelHandler       $duelHandler
     * @param TournamentHandler $tournamentHandler
     */
    public function __construct(DuelHandler $duelHandler, TournamentHandler $tournamentHandler)
    {
        $this->duelHandler = $duelHandler;
        $this->tournamentHandler = $tournamentHandler;
    }

    public function onExternalEvent(ExternalEvent $externalEvent)
    {
        $this->duelHandler->handleExternalEvent($externalEvent->getEvent());
        $this->tournamentHandler->handleExternalEvent($externalEvent->getEvent());
    }
}
