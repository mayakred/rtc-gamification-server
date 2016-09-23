<?php

namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class CallType extends AbstractEnumType
{
    const COLD   = 'cold';
    const HOT    = 'hot';

    /**
     * @var array
     */
    protected static $choices = [
        self::COLD => 'cold',
        self::HOT => 'hot',
    ];
}
