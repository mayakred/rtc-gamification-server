<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Tournament.
 *
 * @ORM\Table(name="tournaments__teams_participants")
 * @ORM\Entity()
 *
 * @JMS\ExclusionPolicy("all")
 */
class TournamentTeamParticipant
{
    const INFO_CARD   = 'tournament_team_participant__info';
    const PUBLIC_CARD = 'tournament_team_participant__public';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Expose()
     * @JMS\Groups({TournamentTeamParticipant::PUBLIC_CARD, TournamentTeamParticipant::INFO_CARD})
     */
    private $id;

    /**
     * @var TournamentTeam
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TournamentTeam", inversedBy="participants")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @JMS\Expose()
     * @JMS\Groups({TournamentTeamParticipant::PUBLIC_CARD, TournamentTeamParticipant::INFO_CARD})
     */
    private $team;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @JMS\Expose()
     * @JMS\Groups({TournamentTeamParticipant::PUBLIC_CARD, TournamentTeamParticipant::INFO_CARD})
     */
    private $user;

    /**
     * @var MetricValue[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MetricValue", mappedBy="participant", cascade={"persist", "remove"})
     *
     * @JMS\Expose()
     * @JMS\SerializedName("statistic")
     * @JMS\Groups({TournamentTeam::PUBLIC_CARD})
     */
    private $values;

    public function __construct()
    {
        $this->values = new ArrayCollection();
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

    /**
     * @return MetricValue[]|ArrayCollection
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param MetricValue|null $metricValue
     *
     * @return $this
     */
    public function addValue(MetricValue $metricValue = null)
    {
        $metricValue->setTeam(null);
        $metricValue->setParticipant($this);
        $this->values->add($metricValue);

        return $this;
    }

    /**
     * @param MetricValue $metricValue
     *
     * @return $this
     */
    public function removeValue(MetricValue $metricValue)
    {
        $metricValue->setTeam(null);
        $metricValue->setParticipant(null);
        $this->values->removeElement($metricValue);

        return $this;
    }
}
