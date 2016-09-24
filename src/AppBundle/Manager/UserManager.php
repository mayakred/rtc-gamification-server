<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 22.09.16
 * Time: 15:56.
 */
namespace AppBundle\Manager;

use AppBundle\Entity\TemporaryEntity;
use AppBundle\Entity\User;
use Doctrine\ORM\NonUniqueResultException;

class UserManager extends BaseEntityManager
{
    /**
     * @param $token
     *
     * @return User|null
     */
    public function findOneByActiveAccessToken($token)
    {
        if (!$token) {
            return null;
        }
        $qb = $this->getRepository()->createQueryBuilder('u')
            ->addSelect('at')
            ->join('u.accessTokens', 'at')
            ->where('at.token like :token')
            ->andWhere(TemporaryEntity::getIsActiveCondition('at'))
            ->setParameter('token', $token)
            ->setParameter('now', new \DateTime(null, new \DateTimeZone('UTC')));

        try {
            $user = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $exception) {
            $user = null;
        }

        return $user;
    }

    /**
     * @param string $phone
     *
     * @return User|null
     */
    public function findOneByActivePhone($phone)
    {
        $qb = $this->getRepository()->createQueryBuilder('u')
            ->join('u.phones', 'p')
            ->where('p.phone = :phone')
            ->andWhere(TemporaryEntity::getIsActiveCondition('p'))
            ->setParameter('phone', $phone)
            ->setParameter('now', new \DateTime(null, new \DateTimeZone('UTC')));
        try {
            $user = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            $user = null;
        }

        return $user;
    }

    /**
     * @return User[]
     */
    public function findAllOrderByTopPosition()
    {
        $qb = $this->getRepository()->createQueryBuilder('u')
            ->addSelect('department')
            ->join('u.department', 'department')
            ->orderBy('u.topPosition', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $id
     *
     * @return User|null
     */
    public function findJoinedWithAccessTokens($id)
    {
        $qb = $this->getRepository()->createQueryBuilder('u')
            ->addSelect('at')
            ->leftJoin('u.accessTokens', 'at')
            ->where('u.id = :id')
            ->setParameter('id', $id);

        try {
            $user = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            $user = null;
        }

        return $user;
    }

    public function recalcUserPosition()
    {
        /**
         * @var User[] $users
         */
        $users = $this->getRepository()->createQueryBuilder('u')
            ->addOrderBy('u.rating', 'DESC')
            ->getQuery()
            ->getResult();
        for ($topPosition = 0; $topPosition < count($users); $topPosition++) {
            $users[$topPosition]->setTopPosition($topPosition + 1);
            $this->save($users[$topPosition], false);
        }
        $this->getEntityManager()->flush();
    }
}
