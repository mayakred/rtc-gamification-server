<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 24.09.16
 * Time: 13:34.
 */
namespace AppBundle\DBAL\Types;

use Symfony\Component\Form\AbstractType;

class PushType extends AbstractType
{
    const DUEL_CREATED = 'push_type.duel_created';
    const DUEL_STARTED = 'push_type.duel_started';
    const DUEL_REJECTED = 'push_type.duel_rejected';
    const DUEL_WON = 'push_type.duel_won';
    const DUEL_DEFEATED = 'push_type.duel_defeated';
    const EVENT_REACHED = 'push_type.event_reached';
}
