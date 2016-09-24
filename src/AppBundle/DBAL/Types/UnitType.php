<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 24.09.16
 * Time: 10:22.
 */
namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class UnitType extends AbstractEnumType
{
    const PERCENT = 'unit_type.percent';
    const RUBLES = 'unit_type.rubles';
    const UNITS = 'unit_type.units';

    protected static $choices = [
        self::PERCENT => 'Percents',
        self::RUBLES => 'Rubles',
        self::UNITS => 'Units',
    ];
}
