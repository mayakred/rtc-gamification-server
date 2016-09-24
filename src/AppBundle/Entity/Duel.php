<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 24.09.16
 * Time: 10:12.
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Duel.
 *
 * @ORM\Entity()
 * @ORM\Table(name="app__duels")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Duel extends TemporaryTimestampableEntity
{
    const FULL_CARD = 'duel__full';
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @JMS\Expose()
     * @JMS\Groups({Duel::FULL_CARD})
     */
    protected $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="victim_id", referencedColumnName="id", nullable=false)
     *
     * @JMS\Expose()
     * @JMS\Groups({Duel::FULL_CARD})
     */
    protected $victim;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="initiator_id", referencedColumnName="id", nullable=false)
     *
     * @JMS\Expose()
     * @JMS\Groups({Duel::FULL_CARD})
     */
    protected $initiator;

    /**
     * @var float
     *
     * @ORM\Column(name="victim_value", type="float")
     *
     * @JMS\Expose()
     * @JMS\Groups({Duel::FULL_CARD})
     */
    protected $victimValue;

    /**
     * @var float
     *
     * @ORM\Column(name="initiator_value", type="float")
     *
     * @JMS\Expose()
     * @JMS\Groups({Duel::FULL_CARD})
     */
    protected $initiatorValue;

    /**
     * @var Metric
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Metric")
     * @ORM\JoinColumn(name="metric_id", referencedColumnName="id", nullable=false)
     *
     * @JMS\Expose()
     * @JMS\Groups({Duel::FULL_CARD})
     */
    protected $metric;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="DuelStatusType")
     *
     * @JMS\Expose()
     * @JMS\Groups({Duel::FULL_CARD})
     */
    protected $status;

    public function __construct()
    {
        $this->initiatorValue = 0;
        $this->victimValue = 0;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return User
     */
    public function getVictim()
    {
        return $this->victim;
    }

    /**
     * @param User $victim
     *
     * @return $this
     */
    public function setVictim($victim)
    {
        $this->victim = $victim;

        return $this;
    }

    /**
     * @return User
     */
    public function getInitiator()
    {
        return $this->initiator;
    }

    /**
     * @param User $initiator
     *
     * @return $this
     */
    public function setInitiator($initiator)
    {
        $this->initiator = $initiator;

        return $this;
    }

    /**
     * @return float
     */
    public function getVictimValue()
    {
        return $this->victimValue;
    }

    /**
     * @param float $victimValue
     *
     * @return $this
     */
    public function setVictimValue($victimValue)
    {
        $this->victimValue = $victimValue;

        return $this;
    }

    /**
     * @return float
     */
    public function getInitiatorValue()
    {
        return $this->initiatorValue;
    }

    /**
     * @param float $initiatorValue
     *
     * @return $this
     */
    public function setInitiatorValue($initiatorValue)
    {
        $this->initiatorValue = $initiatorValue;

        return $this;
    }

    /**
     * @return Metric
     */
    public function getMetric()
    {
        return $this->metric;
    }

    /**
     * @param Metric $metric
     *
     * @return $this
     */
    public function setMetric($metric)
    {
        $this->metric = $metric;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return \DateTime
     *
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("start_at")
     * @JMS\Type("Timestamp")
     * @JMS\Groups({Duel::FULL_CARD})
     */
    public function getSince()
    {
        return parent::getSince();
    }

    /**
     * @return \DateTime
     *
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("end_at")
     * @JMS\Type("Timestamp")
     * @JMS\Groups({Duel::FULL_CARD})
     */
    public function getUntil()
    {
        return parent::getUntil();
    }
}
