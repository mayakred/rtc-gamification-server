<?php

namespace AppBundle\Entity;

use AppBundle\DBAL\Types\EventType;
use Doctrine\ORM\Mapping as ORM;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="events__calls")
 * @ORM\Entity
 */
class CallEvent extends Event
{
    /**
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
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
    public function getEventType()
    {
        return EventType::CALL;
    }
}
