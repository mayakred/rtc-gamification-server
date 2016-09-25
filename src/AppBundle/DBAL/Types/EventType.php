<?php

namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class EventType extends AbstractEnumType
{
    const SALE             = 'sale';
    const CALL             = 'call';
    const MEETING          = 'meeting';
    const UNUSUAL_SOLUTION = 'unusual_solution';

    /**
     * @var array
     */
    protected static $choices = [
        self::SALE => 'Sale',
        self::CALL => 'Call',
        self::MEETING => 'Meeting',
        self::UNUSUAL_SOLUTION => 'Unusual solution',
    ];
}
