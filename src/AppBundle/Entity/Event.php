<?php

namespace AppBundle\Entity;

use AppBundle\Model\Partial\PhonePartial;
use AppBundle\Model\Partial\PhonePartialInterface;
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
abstract class Event extends TimestampableEntity implements PhonePartialInterface
{
    use PhonePartial;

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
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get event type.
     */
    public function getEventType()
    {
        return null;
    }
}
