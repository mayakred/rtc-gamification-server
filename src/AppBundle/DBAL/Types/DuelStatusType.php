<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 24.09.16
 * Time: 11:08.
 */
namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class DuelStatusType extends AbstractEnumType
{
    const WAITING_VICTIM = 'duel_status_type.waiting_victim';
    const IN_PROGRESS = 'duel_status_type.in_progress';
    const VICTIM_WIN = 'duel_status_type.victim_win';
    const INITIATOR_WIN = 'duel_status_type.initiator_win';
    const DRAW = 'duel_status_type.draw';
    const REJECTED_BY_VICTIM = 'duel_status_type.rejected_by_victim';

    protected static $choices = [
        self::WAITING_VICTIM => self::WAITING_VICTIM,
        self::IN_PROGRESS => self::IN_PROGRESS,
        self::VICTIM_WIN => self::VICTIM_WIN,
        self::INITIATOR_WIN => self::INITIATOR_WIN,
        self::DRAW => self::DRAW,
        self::REJECTED_BY_VICTIM => self::REJECTED_BY_VICTIM,
    ];
}
