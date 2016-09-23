<?php

namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class TournamentType extends AbstractEnumType
{
    const TEAM       = 'team';
    const INDIVIDUAL = 'individual';

    /**
     * @var array
     */
    protected static $choices = [
        self::TEAM       => 'Командный',
        self::INDIVIDUAL => 'Индвидуальный',
    ];
}
