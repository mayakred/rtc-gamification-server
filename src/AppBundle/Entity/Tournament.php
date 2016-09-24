<?php

namespace AppBundle\Entity;

use AppBundle\DBAL\Types\TournamentType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use JMS\Serializer\Annotation as JMS;

/**
 * Tournament.
 *
 * @ORM\Table(name="tournaments__tournaments")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TournamentRepository")
 * @ORM\HasLifecycleCallbacks()
 * @JMS\ExclusionPolicy("all")
 */
class Tournament extends TimestampableEntity
{
    const SHORT_CARD  = 'tournament__short';
    const PUBLIC_CARD = 'tournament__public';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Expose()
     * @JMS\Groups({Tournament::SHORT_CARD, Tournament::PUBLIC_CARD})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @JMS\Expose()
     * @JMS\Groups({Tournament::SHORT_CARD, Tournament::PUBLIC_CARD})
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="datetime")
     *
     * @JMS\Expose()
     * @JMS\Type("Timestamp")
     * @JMS\SerializedName("start_at")
     * @JMS\Groups({Tournament::SHORT_CARD, Tournament::PUBLIC_CARD})
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetime")
     *
     * @JMS\Expose()
     * @JMS\Type("Timestamp")
     * @JMS\SerializedName("end_at")
     * @JMS\Groups({Tournament::SHORT_CARD, Tournament::PUBLIC_CARD})
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="TournamentType")
     * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\TournamentType")
     *
     * @JMS\Expose()
     * @JMS\Groups({Tournament::SHORT_CARD, Tournament::PUBLIC_CARD})
     */
    private $type;

    /**
     * @var TournamentTeam[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TournamentTeam", mappedBy="tournament", cascade={"persist", "remove"}, orphanRemoval=true)
     *
     * @JMS\Expose()
     * @JMS\Groups({Tournament::PUBLIC_CARD})
     */
    private $teams;

    /**
     * @var TournamentMetricCondition[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TournamentMetricCondition", mappedBy="tournament", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $metricConditions;

    /**
     * Tournament constructor.
     */
    public function __construct()
    {
        $this->teams = new ArrayCollection();
    }

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
     * Set name.
     *
     * @param string $name
     *
     * @return Tournament
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set startDate.
     *
     * @param \DateTime $startDate
     *
     * @return Tournament
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate.
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate.
     *
     * @param \DateTime $endDate
     *
     * @return Tournament
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate.
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Tournament
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param TournamentTeam $team
     *
     * @return Tournament
     */
    public function addTeam(TournamentTeam $team)
    {
        $team->setTournament($this);
        $this->teams[] = $team;

        return $this;
    }

    /**
     * @param TournamentTeam $team
     *
     * @return Tournament
     */
    public function removeTeam(TournamentTeam $team)
    {
        $team->setTournament(null);
        $this->teams->removeElement($team);

        return $this;
    }

    /**
     * @return TournamentTeam[]|ArrayCollection
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @param TournamentMetricCondition $metricCondition
     *
     * @return Tournament
     */
    public function addMetricCondition(TournamentMetricCondition $metricCondition)
    {
        $metricCondition->setTournament($this);
        $this->metricConditions[] = $metricCondition;

        return $this;
    }

    /**
     * @param TournamentMetricCondition $metricCondition
     *
     * @return Tournament
     */
    public function removeMetricCondition(TournamentMetricCondition $metricCondition)
    {
        $metricCondition->setTournament(null);
        $this->metricConditions->removeElement($metricCondition);

        return $this;
    }

    /**
     * @return TournamentMetricCondition[]|ArrayCollection
     */
    public function getMetricConditions()
    {
        return $this->metricConditions;
    }

    /**
     * @return bool
     */
    public function isIndividual()
    {
        return $this->type === TournamentType::INDIVIDUAL;
    }
}
