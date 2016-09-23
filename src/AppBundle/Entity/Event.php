<?php

namespace AppBundle\Entity;

use AppBundle\Model\Phone;
use Doctrine\ORM\Mapping as ORM;

/**
 * Event.
 *
 * @ORM\Table(name="events__events")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="EventType")
 * @ORM\DiscriminatorMap({
 *   "sale"    = "SaleEvent",
 *   "call"    = "CallEvent",
 *   "meeting" = "MeetingEvent",
 * })
 * @ORM\HasLifecycleCallbacks()
 */
abstract class Event extends TimestampableEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $user;

    /**
     * @var Phone
     */
    protected $phone;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user.
     *
     * @param User $user
     *
     * @return Event
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param Phone|null $phone
     * @return Event
     */
    public function setPhone(Phone $phone = null)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Phone|null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Get event type.
     */
    public function getEventType()
    {
        return null;
    }
}
