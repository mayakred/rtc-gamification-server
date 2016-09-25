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
            ->addSelect('initiator_avatar', 'victim_avatar')
            ->addSelect('initiator_department', 'initiator_user_achievements', 'victim_department', 'victim_user_achievements')
            ->join('duel.initiator', 'initiator')
            ->join('initiator.avatar', 'initiator_avatar')
            ->join('initiator.department', 'initiator_department')
            ->join('initiator.userAchievements', 'initiator_user_achievements')
            ->join('duel.victim', 'victim')
            ->join('victim.avatar', 'victim_avatar')
            ->join('victim.userAchievements', 'victim_user_achievements')
            ->join('victim.department', 'victim_department')
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

    /**
     * @return Duel[]
     */
    public function findFinishedDuels()
    {
        $qb = $this->getRepository()->createQueryBuilder('duel')
            ->where('duel.status = :in_progress')
            ->andWhere('duel.until < :now')
            ->setParameter('now', new \DateTime(null, new \DateTimeZone('UTC')))
            ->setParameter('in_progress', DuelStatusType::IN_PROGRESS);

        return $qb->getQuery()->getResult();
    }
}
