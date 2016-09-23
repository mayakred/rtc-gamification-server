<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 23.09.16
 * Time: 11:58.
 */
namespace AppBundle\Handler;

use AppBundle\Entity\AccessToken;
use AppBundle\Event\UserAuthorizationEvent;
use AppBundle\Manager\AccessTokenManager;
use AppBundle\Manager\DeviceManager;
use AppBundle\Manager\PhoneManager;
use AppBundle\Manager\UserManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AuthHandler
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var DeviceManager
     */
    protected $deviceManager;

    /**
     * @var AccessToken
     */
    protected $tokenManager;

    /**
     * @var PhoneManager
     */
    protected $phoneManager;

    /**
     * @var UserHandler
     */
    protected $userHandler;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var bool
     */
    protected $passwordGenerationDisabled;

    /**
     * AuthHandler constructor.
     *
     * @param UserManager              $userManager
     * @param DeviceManager            $deviceManager
     * @param AccessTokenManager       $tokenManager
     * @param PhoneManager             $phoneManager
     * @param UserHandler              $userHandler
     * @param EventDispatcherInterface $dispatcher
     * @param $passwordGenerationDisabled
     */
    public function __construct(
        UserManager $userManager,
        DeviceManager $deviceManager,
        AccessTokenManager $tokenManager,
        PhoneManager $phoneManager,
        UserHandler $userHandler,
        EventDispatcherInterface $dispatcher,
        $passwordGenerationDisabled
    ) {
        $this->userManager = $userManager;
        $this->deviceManager = $deviceManager;
        $this->tokenManager = $tokenManager;
        $this->phoneManager = $phoneManager;
        $this->userHandler = $userHandler;
        $this->dispatcher = $dispatcher;
        $this->passwordGenerationDisabled = $passwordGenerationDisabled;
    }

    /**
     * @param string $phone
     *
     * @return string
     */
    public function request($phone)
    {
        $user = $this->userHandler->getOrCreateUserByPhone($phone);
        $secret = uniqid('', true);
        $smsCode = $this->passwordGenerationDisabled ? 12345 : random_int(10000, 99999);
        $user->setSecret($secret);
        $user->setSmsCode($smsCode);
        $user->setSmsCodeDt(new \DateTime(null, new \DateTimeZone('UTC')));
        $this->userManager->save($user);

        $event = new UserAuthorizationEvent($user, $user->getSmsCode(), $phone);
        $this->dispatcher->dispatch(UserAuthorizationEvent::NAME, $event);

        return $user->getSecret();
    }
}
