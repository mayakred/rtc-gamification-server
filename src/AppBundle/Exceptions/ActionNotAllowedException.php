<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 24.09.16
 * Time: 12:19.
 */
namespace AppBundle\Exceptions;

class ActionNotAllowedException extends BaseException
{
    public function getHttpCode()
    {
        return 403;
    }

    public function getName()
    {
        return 'ActionNotAllowed';
    }
}
