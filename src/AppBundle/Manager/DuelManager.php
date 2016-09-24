<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 24.09.16
 * Time: 11:27.
 */
namespace AppBundle\Manager;

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
            ->leftJoin('duel.initiator', 'initiator')
            ->leftJoin('duel.victim', 'victim')
            ->where('initiator = :user')
            ->orWhere('victim = :user')
            ->setParameter('user', $user)
            ->orderBy('duel.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
