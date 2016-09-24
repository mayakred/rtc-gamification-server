<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tournament.
 *
 * @ORM\Table(name="tournaments__teams_participants")
 * @ORM\Entity()
 */
class TournamentTeamParticipant
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
     * @var TournamentTeam
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TournamentTeam", inversedBy="participants")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $team;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $user;

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
     * @param TournamentTeam|null $team
     *
     * @return TournamentTeamParticipant
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

    /**
     * @param User $user
     *
     * @return TournamentTeamParticipant
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
