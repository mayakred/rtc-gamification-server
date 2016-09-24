<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 24.09.16
 * Time: 19:06.
 */
namespace AppBundle\Manager;

use AppBundle\Entity\Tournament;
use AppBundle\Entity\TournamentTeamParticipant;
use AppBundle\Entity\User;

class TournamentTeamParticipantManager extends BaseEntityManager
{
    /**
     * @param Tournament $tournament
     * @param User       $user
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return TournamentTeamParticipant
     */
    public function findOneByTournamentAndUser(Tournament $tournament, User $user)
    {
        $qb = $this->getRepository()->createQueryBuilder('participant')
            ->addSelect('team')
            ->addSelect('team_values')
            ->addSelect('participant_values')
            ->join('participant.team', 'team')
            ->join('team.values', 'team_values')
            ->join('participant.values', 'participant_values')
            ->where('team.tournament = :tournament')
            ->andWhere('participant.user = :user')
            ->setParameter('tournament', $tournament)
            ->setParameter('user', $user);

        return $qb->getQuery()->getSingleResult();
    }
}
