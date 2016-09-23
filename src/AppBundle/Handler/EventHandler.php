<?php

namespace AppBundle\Handler;

use AppBundle\Entity\Event;
use AppBundle\Manager\EventManager;

class EventHandler
{
    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * UserHandler constructor.
     *
     * @param EventManager $eventManager
     */
    public function __construct(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * @param Event $event
     *
     * @return Event
     */
    public function add(Event $event)
    {
        $this->eventManager->save($event);

        return $event;
    }
}
