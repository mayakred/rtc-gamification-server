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
use AppBundle\Manager\UserManager;

class UserHandler
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * UserHandler constructor.
     *
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
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
     * @param $phone
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
}
