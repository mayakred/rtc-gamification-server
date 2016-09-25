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
            ->addSelect('user')
            ->addSelect('department')
            ->addSelect('metric')
            ->addSelect('image')
            ->join('participant.team', 'team')
            ->join('team.values', 'team_values')
            ->join('participant.values', 'participant_values')
            ->join('participant_values.metric', 'metric')
            ->join('participant.user', 'user')
            ->join('user.department', 'department')
            ->join('user.avatar', 'image')
            ->where('team.tournament = :tournament')
            ->andWhere('user.id = :user_id')
            ->setParameter('tournament', $tournament)
            ->setParameter('user_id', $user->getId());

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * @param Tournament $tournament
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return TournamentTeamParticipant[]
     */
    public function findByTournament(Tournament $tournament)
    {
        $qb = $this->getRepository()->createQueryBuilder('participant')
            ->addSelect('participant_values')
            ->addSelect('metric')
            ->addSelect('user')
            ->addSelect('image')
            ->addSelect('department')
            ->join('participant.team', 'team')
            ->join('participant.user', 'user')
            ->join('user.avatar', 'image')
            ->join('user.department', 'department')
            ->join('participant.values', 'participant_values')
            ->join('participant_values.metric', 'metric')
            ->where('team.tournament = :tournament')
            ->setParameter('tournament', $tournament);

        return $qb->getQuery()->getResult();
    }
}
