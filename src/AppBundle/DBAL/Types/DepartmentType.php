<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 23.09.16
 * Time: 19:30.
 */
namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class DepartmentType extends AbstractEnumType
{
    const SERVICE = 'department_type.service';
    const FEDERAL = 'department_type.federal_clients';
    const SALES = 'department_type.active_sales';

    protected static $choices = [
        self::SERVICE => self::SERVICE,
        self::FEDERAL => self::FEDERAL,
        self::SALES => self::SALES,
    ];
}
