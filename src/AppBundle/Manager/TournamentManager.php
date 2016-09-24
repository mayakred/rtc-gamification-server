<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Tournament;
use AppBundle\Entity\TournamentMetricCondition;
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
     * @var MetricManager
     */
    private $metricManager;

    /**
     * @param UserManager $userManager
     */
    public function setUserManager(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param MetricManager $metricManager
     */
    public function setMetricManager(MetricManager $metricManager)
    {
        $this->metricManager = $metricManager;
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
     * @param User $user
     *
     * @return Tournament[]
     */
    public function findActiveByUser(User $user)
    {
        return $this
            ->getRepository()
            ->createQueryBuilder('t')
            ->join('t.teams', 'teams')
            ->join('teams.participants', 'p')
            ->join('p.user', 'user')
            ->where('p.user = :user')
            ->andWhere('t.endDate > :cur_date')
            ->setParameter('cur_date', new \DateTime('now', new \DateTimeZone('UTC')))
            ->setParameter('user', $user)
            ->orderBy('id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findFullInfo($id)
    {
        return $this
            ->getRepository()
            ->createQueryBuilder('t')
            ->join('t.teams', 'teams')
            ->join('teams.participants', 'p')
            ->join('p.user', 'user')
            ->where('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Tournament $tournament
     */
    private function addTeams(Tournament $tournament)
    {
        $users = $this->userManager->findAllOrderByTopPosition();
        $metrics = $tournament->isIndividual() ? $this->metricManager->findAvailableForTeamTournaments() : [];

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

                    foreach ($metrics as $metric) {
                        $metricCondition = new TournamentMetricCondition();
                        $metricCondition
                            ->setMetric($metric)
                            ->setDepartment($department)
                            ->setMoneyLimit(random_int(100, 500)) # hack for hackathon
                            ->setAmountLimit(random_int(300, 500)) # hack for hackathon
                        ;

                        $tournament->addMetricCondition($metricCondition);
                    }
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
