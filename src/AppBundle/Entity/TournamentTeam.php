<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Tournament.
 *
 * @ORM\Table(name="tournaments__teams")
 * @ORM\Entity()
 * @JMS\ExclusionPolicy("all")
 */
class TournamentTeam
{
    const SHORT_CARD  = 'tournament_team__short';
    const PUBLIC_CARD = 'tournament_team__public';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Expose()
     * @JMS\Groups({TournamentTeam::PUBLIC_CARD, TournamentTeam::SHORT_CARD})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Department")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     *
     * @JMS\Expose()
     * @JMS\Groups({TournamentTeam::PUBLIC_CARD, TournamentTeam::SHORT_CARD})
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
     *
     * @JMS\Expose()
     * @JMS\Groups({TournamentTeam::PUBLIC_CARD})
     */
    private $participants;

    /**
     * TournamentTeam constructor.
     */
    public function __construct()
    {
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
     * @param TournamentTeamParticipant $participant
     *
     * @return TournamentTeam
     */
    public function addParticipant(TournamentTeamParticipant $participant)
    {
        $participant->setTeam($this);
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
        $participant->setTeam(null);
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
