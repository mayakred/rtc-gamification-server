<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 12.02.16
 * Time: 16:29.
 */
namespace AppBundle\Exceptions;

class RequestExpiredException extends BaseException
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'RequestExpired';
    }

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return 403;
    }
}
