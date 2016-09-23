<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 23.09.16
 * Time: 12:00.
 */
namespace AppBundle\Handler;

use AppBundle\Entity\Phone;
use AppBundle\Entity\User;
use AppBundle\Exceptions\AccessTokenInvalidException;
use AppBundle\Manager\AccessTokenManager;
use AppBundle\Manager\UserManager;

class UserHandler
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var AccessTokenManager
     */
    protected $accessTokenManager;

    /**
     * UserHandler constructor.
     *
     * @param UserManager        $userManager
     * @param AccessTokenManager $accessTokenManager
     */
    public function __construct(UserManager $userManager, AccessTokenManager $accessTokenManager)
    {
        $this->userManager = $userManager;
        $this->accessTokenManager = $accessTokenManager;
    }

    public function prepareUserWithPhone($phone)
    {
        $user = new User();
        $userPhone = new Phone();
        $userPhone->setPhone($phone);
        $user->addPhone($userPhone);

        return $user;
    }

    /**
     * @param string $phone
     *
     * @return \AppBundle\Entity\User
     */
    public function getOrCreateUserByPhone($phone)
    {
        $user = $this->userManager->findOneByActivePhone($phone);
        if (null === $user) {
            $user = $this->prepareUserWithPhone($phone);
            $this->userManager->save($user);
        }

        return $user;
    }

    /**
     * @param string $playerId
     * @param User   $user
     *
     * @throws AccessTokenInvalidException
     */
    public function addPlayerId($playerId, User $user)
    {
        $currentUserAccessToken = $this->accessTokenManager->findOneActiveByUserAndToken($user, $user->getRequestToken());
        if ($currentUserAccessToken === null) {
            throw new AccessTokenInvalidException();
        }
        $this->accessTokenManager->deactivateActiveTokensWithPlayerId($playerId);
        $currentUserAccessToken->setPlayerId($playerId);
        $this->accessTokenManager->save($currentUserAccessToken);
    }
}
