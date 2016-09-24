<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 24.09.16
 * Time: 11:27.
 */
namespace AppBundle\Manager;

use AppBundle\DBAL\Types\DuelStatusType;
use AppBundle\Entity\Duel;
use AppBundle\Entity\User;

class DuelManager extends BaseEntityManager
{
    /**
     * @param User $user
     *
     * @return Duel[]
     */
    public function findAllRelatedToUser(User $user)
    {
        $qb = $this->getRepository()->createQueryBuilder('duel')
            ->addSelect('initiator')
            ->addSelect('victim')
            ->addSelect('metric')
            ->leftJoin('duel.initiator', 'initiator')
            ->leftJoin('duel.victim', 'victim')
            ->join('duel.metric', 'metric')
            ->where('initiator = :user')
            ->orWhere('victim = :user')
            ->setParameter('user', $user)
            ->orderBy('duel.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param User $user
     *
     * @return Duel[]
     */
    public function findActiveDuelsRelatedToUser(User $user)
    {
        $qb = $this->getRepository()->createQueryBuilder('duel')
            ->addSelect('initiator')
            ->addSelect('victim')
            ->addSelect('metric')
            ->leftJoin('duel.initiator', 'initiator')
            ->leftJoin('duel.victim', 'victim')
            ->join('duel.metric', 'metric')
            ->where('(initiator = :user OR victim = :user)')
            ->andWhere('duel.status = :in_progress')
            ->setParameter('user', $user)
            ->setParameter('in_progress', DuelStatusType::IN_PROGRESS)
            ->orderBy('duel.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
