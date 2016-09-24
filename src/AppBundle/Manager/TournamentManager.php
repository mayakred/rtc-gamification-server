<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Tournament;
use AppBundle\Entity\TournamentTeam;
use AppBundle\Entity\TournamentTeamParticipant;
use AppBundle\Entity\User;

class TournamentManager extends BaseEntityManager
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @param UserManager $userManager
     */
    public function setUserManager(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param string $name
     * @param string $type
     * @param string $period
     */
    public function add($name, $type, $period)
    {
        $startDt = new \DateTime('now', new \DateTimeZone('UTC'));
        $endDt = clone $startDt;
        $endDt->add(new \DateInterval(sprintf('P%dD', $period)));

        $tournament = new Tournament();
        $tournament
            ->setName($name)
            ->setType($type)
            ->setStartDate($startDt)
            ->setEndDate($endDt)
        ;

        $this->addTeams($tournament);

        $this->save($tournament);
    }

    /**
     * @param Tournament $tournament
     */
    private function addTeams(Tournament $tournament)
    {
        $users = $this->userManager->findAllOrderByTopPosition();

        $teams = [];
        foreach ($users as $user) {
            /** @var TournamentTeam|null $team */
            $team = null;
            if ($tournament->isIndividual()) {
                $team = new TournamentTeam();
            } else {
                $department = $user->getDepartment();
                if (!array_key_exists($department->getId(), $teams)) {
                    $teams[$department->getId()] = new TournamentTeam();
                    $teams[$department->getId()]->setDepartment($department);
                }

                /** @var TournamentTeam $team */
                $team = $teams[$department->getId()];
            }

            $team->addParticipant($this->createParticipant($user));

            $tournament->addTeam($team);
        }
    }

    /**
     * @param User $user
     *
     * @return TournamentTeamParticipant
     */
    private function createParticipant(User $user)
    {
        $participant = new TournamentTeamParticipant();
        $participant->setUser($user);

        return $participant;
    }
}
