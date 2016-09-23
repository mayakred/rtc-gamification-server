<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tournament.
 *
 * @ORM\Table(name="tournaments__teams_results")
 * @ORM\Entity()
 */
class TournamentTeamResult
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
     * @var float
     *
     *
     * @ORM\Column(name="value", type="float")
     */
    private $value;

    /**
     * @var TournamentTeam
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TournamentTeam", inversedBy="results")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $team;

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
     * @param float $value
     *
     * @return TournamentTeamResult
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param TournamentTeam|null $team
     *
     * @return TournamentTeamResult
     */
    public function setTeam(TournamentTeam $team = null)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * @return TournamentTeam
     */
    public function getTeam()
    {
        return $this->team;
    }
}
