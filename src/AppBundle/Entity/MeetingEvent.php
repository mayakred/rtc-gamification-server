<?php

namespace AppBundle\Entity;

use AppBundle\DBAL\Types\EventType;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="events__meetings")
 * @ORM\Entity
 */
class MeetingEvent extends Event
{
    /**
     * @var bool
     *
     * @ORM\Column(name="result", type="boolean", options={"default" = false})
     */
    private $result = false;

    /**
     * @param bool $result
     *
     * @return MeetingEvent
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * @return bool
     */
    public function isResult()
    {
        return $this->result;
    }

    /**
     * {@inheritdoc}
     */
    public function getEventType()
    {
        return EventType::MEETING;
    }
}
