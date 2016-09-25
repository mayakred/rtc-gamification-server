<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 25.09.16
 * Time: 12:10.
 */
namespace AppBundle\Event;

use AppBundle\Entity\Duel;
use Symfony\Component\EventDispatcher\Event;

class DuelEvent extends Event
{
    const NAME = 'app.event.duel';

    /**
     * @var Duel
     */
    protected $duel;

    /**
     * InternalEvent constructor.
     *
     * @param Duel $duel
     */
    public function __construct(Duel $duel)
    {
        $this->duel = $duel;
    }

    /**
     * @return Duel
     */
    public function getDuel()
    {
        return $this->duel;
    }

    /**
     * @param Duel $duel
     *
     * @return $this
     */
    public function setDuel($duel)
    {
        $this->duel = $duel;

        return $this;
    }
}
