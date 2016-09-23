<?php

namespace AppBundle\Entity;

use AppBundle\DBAL\Types\EventType;
use Doctrine\ORM\Mapping as ORM;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Table(name="events__calls")
 * @ORM\Entity
 */
class CallEvent extends Event
{
    /**
     * @var string
     *
     * @ORM\Column(name="call_type", type="CallType")
     * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\CallType")
     */
    private $callType;

    /**
     * @param string $callType
     *
     * @return CallEvent
     */
    public function setCallType($callType)
    {
        $this->callType = $callType;

        return $this;
    }

    /**
     * @return string
     */
    public function getCallType()
    {
        return $this->callType;
    }

    /**
     * {@inheritdoc}
     */
    public function getTaskType()
    {
        return EventType::CALL;
    }
}
