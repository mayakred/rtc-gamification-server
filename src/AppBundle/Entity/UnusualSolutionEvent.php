<?php

namespace AppBundle\Entity;

use AppBundle\DBAL\Types\EventType;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="events__unusual")
 * @ORM\Entity
 */
class UnusualSolutionEvent extends Event
{
    /**
     * {@inheritdoc}
     */
    public function getEventType()
    {
        return EventType::UNUSUAL_SOLUTION;
    }
}
