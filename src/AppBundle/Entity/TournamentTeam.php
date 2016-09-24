<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tournament.
 *
 * @ORM\Table(name="tournaments__teams")
 * @ORM\Entity()
 */
class TournamentTeam
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Department")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $department;

    /**
     * @var Tournament
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tournament")
     * @ORM\JoinColumn(name="tournament_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $tournament;

    /**
     * @var TournamentTeamParticipant[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TournamentTeamParticipant", mappedBy="team", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $participants;

    /**
     * @var TournamentTeamResult[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TournamentTeamResult", mappedBy="team", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $results;

    /**
     * TournamentTeam constructor.
     */
    public function __construct()
    {
        $this->results = new ArrayCollection();
        $this->participants = new ArrayCollection();
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
     * @param string $department
     *
     * @return TournamentTeam
     */
    public function setDepartment($department)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return string
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param Tournament|null $tournament
     *
     * @return TournamentTeam
     */
    public function setTournament(Tournament $tournament = null)
    {
        $this->tournament = $tournament;

        return $this;
    }

    /**
     * @return Tournament
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    /**
     * @param TournamentTeamResult $result
     *
     * @return TournamentTeam
     */
    public function addResult(TournamentTeamResult $result)
    {
        $result->setTeam($this);
        $this->results[] = $result;

        return $this;
    }

    /**
     * @param TournamentTeamResult $result
     *
     * @return TournamentTeam
     */
    public function removeResult(TournamentTeamResult $result)
    {
        $result->setTeam(null);
        $this->results->removeElement($result);

        return $this;
    }

    /**
     * @return TournamentTeamResult[]|ArrayCollection
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param TournamentTeamParticipant $participant
     *
     * @return TournamentTeam
     */
    public function addParticipant(TournamentTeamParticipant $participant)
    {
        $participant->setTournament($this);
        $this->participants[] = $participant;

        return $this;
    }

    /**
     * @param TournamentTeamParticipant $participant
     *
     * @return TournamentTeam
     */
    public function removeParticipant(TournamentTeamParticipant $participant)
    {
        $participant->setTournament(null);
        $this->participants->removeElement($participant);

        return $this;
    }

    /**
     * @return TournamentTeamParticipant[]|ArrayCollection
     */
    public function getParticipants()
    {
        return $this->participants;
    }
}
