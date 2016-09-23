<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 23.09.16
 * Time: 11:44.
 */
namespace AppBundle\Model;

use AppBundle\Model\Partial\PhonePartial;
use AppBundle\Model\Partial\PhonePartialInterface;

class Phone implements PhonePartialInterface
{
    use PhonePartial;
}
