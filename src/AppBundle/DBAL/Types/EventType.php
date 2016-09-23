<?php

namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class EventType extends AbstractEnumType
{
    const SALE    = 'sale';
    const CALL    = 'call';
    const MEETING = 'meeting';

    /**
     * @var array
     */
    protected static $choices = [
        self::SALE => 'Sale',
        self::CALL => 'Call',
        self::MEETING => 'Meeting',
    ];
}
