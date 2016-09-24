<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;

/**
 * Tournament.
 *
 * @ORM\Table(name="tournaments__tournaments")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TournamentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Tournament extends TimestampableEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetime")
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="TournamentType")
     * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\TournamentType")
     */
    private $type;

    /**
     * @var TournamentTeam[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TournamentTeam", mappedBy="tournament", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $teams;

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
}
