<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 24.09.16
 * Time: 20:18.
 */
namespace AppBundle\Handler;

use AppBundle\Entity\Event;
use AppBundle\Manager\TournamentManager;
use AppBundle\Manager\TournamentTeamParticipantManager;

class TournamentHandler
{
    /**
     * @var EventHandler
     */
    protected $eventHandler;

    /**
     * @var TournamentManager
     */
    protected $tournamentManager;

    /**
     * @var TournamentTeamParticipantManager
     */
    protected $tournamentTeamParticipantManager;

    /**
     * TournamentHandler constructor.
     *
     * @param EventHandler                     $eventHandler
     * @param TournamentManager                $tournamentManager
     * @param TournamentTeamParticipantManager $tournamentTeamParticipantManager
     */
    public function __construct(EventHandler $eventHandler, TournamentManager $tournamentManager, TournamentTeamParticipantManager $tournamentTeamParticipantManager)
    {
        $this->eventHandler = $eventHandler;
        $this->tournamentManager = $tournamentManager;
        $this->tournamentTeamParticipantManager = $tournamentTeamParticipantManager;
    }

    public function handleExternalEvent(Event $event)
    {
        $user = $event->getUser();
        $eventMetrics = $this->eventHandler->convertEventToMetrics($event);
        $tournaments = $this->tournamentManager->findActiveByUser($event->getUser());
        foreach ($tournaments as $tournament) {
            $participant = $this->tournamentTeamParticipantManager
                ->findOneByTournamentAndUser($tournament, $user);
            foreach ($eventMetrics as $metricCode => $metricValue) {
                foreach ($participant->getValues() as $participantValue) {
                    if ($participantValue->getMetric()->getCode() === $metricCode) {
                        $participantValue->add($metricValue);
                    }
                }
                foreach ($participant->getTeam()->getValues() as $teamValue) {
                    if ($teamValue->getMetric()->getCode() === $metricCode) {
                        $teamValue->add($metricValue);
                    }
                }
            }
        }
        $this->tournamentManager->getEntityManager()->flush();
    }
}
