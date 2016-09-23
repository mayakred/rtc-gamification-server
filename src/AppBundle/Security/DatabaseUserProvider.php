<?php

namespace AppBundle\Security;

use AppBundle\Entity\User;
use AppBundle\Manager\UserManager;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class DatabaseUserProvider implements UserProviderInterface
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * DbUserProvider constructor.
     *
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function loadUserById($id)
    {
        return $this->userManager->find($id);
    }

    /**
     * @param string $username
     *
     * @return mixed|null
     */
    public function loadUserByUsername($username)
    {
        $id = $username;
        $user = $this->userManager->find($id);
        if ($user) {
            return $user;
        }

        throw new UsernameNotFoundException();
    }

    /**
     * @param $accessToken
     *
     * @return null|User
     */
    public function getUserForAccessToken($accessToken)
    {
        return $this->userManager->findOneByActiveAccessToken($accessToken);
    }

    /**
     * @param UserInterface $user
     *
     * @return mixed|null
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException();
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === User::class;
    }
}
