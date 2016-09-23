<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 23.09.16
 * Time: 12:00.
 */
namespace AppBundle\Manager;

use AppBundle\Entity\AccessToken;
use AppBundle\Entity\TemporaryEntity;
use AppBundle\Entity\User;

class AccessTokenManager extends BaseEntityManager
{
    /**
     * @param User $user
     * @param $token
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return AccessToken
     */
    public function findOneActiveByUserAndToken(User $user, $token)
    {
        return $this->getRepository()->createQueryBuilder('access_token')
            ->where('access_token.user = :user')
            ->andWhere('access_token.token = :token')
            ->andWhere(TemporaryEntity::getIsActiveCondition('access_token'))
            ->setParameter('user', $user)
            ->setParameter('token', $token)
            ->setParameter('now', new \DateTime(null, new \DateTimeZone('UTC')))
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function deactivateActiveTokensWithPlayerId($playerId)
    {
        $qb = $this->getRepository()->createQueryBuilder('at');
        $qb
            ->update('AppBundle:AccessToken', 'at')
            ->set('at.playerId', ':null')
            ->where('at.playerId = :playerId')
            ->andWhere(TemporaryEntity::getIsActiveCondition('at'))
            ->setParameter('now', new \DateTime(null, new \DateTimeZone('UTC')))
            ->setParameter('null', null)
            ->setParameter('playerId', $playerId);

        $qb->getQuery()->execute();
    }
}
