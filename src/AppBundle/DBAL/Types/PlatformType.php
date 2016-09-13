<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 13.09.16
 * Time: 16:18.
 */
namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class PlatformType extends AbstractEnumType
{
    const ANDROID = 'android';
    const IOS = 'ios';

    protected static $choices = [
        self::ANDROID => 'Android',
        self::IOS => 'iOS',
    ];
}
