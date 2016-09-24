<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 24.09.16
 * Time: 10:13.
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Metric.
 *
 * @ORM\Entity()
 * @ORM\Table(name="app__metrics")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Metric
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @JMS\Expose()
     * @JMS\Groups({"all"})
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", unique=true)
     *
     * @JMS\Expose()
     * @JMS\Groups({"all"})
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="unit_type", type="UnitType")
     *
     * @JMS\Expose()
     * @JMS\Groups({"all"})
     */
    protected $unitType;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     *
     * @JMS\Expose()
     * @JMS\Groups({"all"})
     */
    protected $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="available_for_individual_tournaments", type="boolean")
     *
     * @JMS\Expose()
     * @JMS\Groups({"all"})
     */
    protected $availableForIndividualTournaments;

    /**
     * @var bool
     *
     * @ORM\Column(name="available_for_team_tournaments", type="boolean")
     *
     * @JMS\Expose()
     * @JMS\Groups({"all"})
     */
    protected $availableForTeamTournaments;

    /**
     * @var bool
     *
     * @ORM\Column(name="available_for_duel", type="boolean")
     *
     * @JMS\Expose()
     * @JMS\Groups({"all"})
     */
    protected $availableForDuel;

    /**
     * Metric constructor.
     */
    public function __construct()
    {
        $this->availableForIndividualTournaments = false;
        $this->availableForTeamTournaments = true;
        $this->availableForDuel = true;
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
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getUnitType()
    {
        return $this->unitType;
    }

    /**
     * @param string $unitType
     *
     * @return $this
     */
    public function setUnitType($unitType)
    {
        $this->unitType = $unitType;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAvailableForIndividualTournaments()
    {
        return $this->availableForIndividualTournaments;
    }

    /**
     * @param bool $availableForIndividualTournaments
     *
     * @return $this
     */
    public function setAvailableForIndividualTournaments($availableForIndividualTournaments)
    {
        $this->availableForIndividualTournaments = $availableForIndividualTournaments;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAvailableForTeamTournaments()
    {
        return $this->availableForTeamTournaments;
    }

    /**
     * @param bool $availableForTeamTournaments
     *
     * @return $this
     */
    public function setAvailableForTeamTournaments($availableForTeamTournaments)
    {
        $this->availableForTeamTournaments = $availableForTeamTournaments;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAvailableForDuel()
    {
        return $this->availableForDuel;
    }

    /**
     * @param bool $availableForDuel
     *
     * @return $this
     */
    public function setAvailableForDuel($availableForDuel)
    {
        $this->availableForDuel = $availableForDuel;

        return $this;
    }
}
