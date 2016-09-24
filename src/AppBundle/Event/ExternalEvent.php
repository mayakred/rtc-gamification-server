<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 24.09.16
 * Time: 15:45.
 */
namespace AppBundle\Event;

use AppBundle\Entity\Event as EventEntity;
use Symfony\Component\EventDispatcher\Event;

class ExternalEvent extends Event
{
    const NAME = 'app.event.external';

    /**
     * @var EventEntity $event
     */
    protected $event;

    /**
     * ExternalEvent constructor.
     *
     * @param Event $event
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * @return EventEntity
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param EventEntity $event
     *
     * @return $this
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }
}
