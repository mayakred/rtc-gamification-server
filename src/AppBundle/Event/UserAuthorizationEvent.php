<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 03.06.16
 * Time: 13:16.
 */
namespace AppBundle\Event;

use AppBundle\Entity\User;

class UserAuthorizationEvent extends SMSNotificationEvent
{
    /**
     * @var string
     */
    protected $code;

    /**
     * UserAuthorizationEvent constructor.
     *
     * @param User   $user
     * @param string $code
     * @param string $phone
     */
    public function __construct(User $user, $code, $phone)
    {
        $this->code = $code;
        $message = $this->constructMessage();
        parent::__construct($user, $message, $phone);
    }

    /**
     * @return string
     */
    public function constructMessage()
    {
        return 'Ваш пароль для входа: ' . $this->code;
    }
}
