<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tournament.
 *
 * @ORM\Table(name="tournaments__metrics_conditions")
 * @ORM\Entity()
 */
class TournamentMetricCondition
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
     * @var Tournament
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tournament", inversedBy="metricConditions")
     * @ORM\JoinColumn(name="tournament_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $tournament;

    /**
     * @var Metric
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Metric")
     * @ORM\JoinColumn(name="metric_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $metric;

    /**
     * @var Department
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Department")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $department;

    /**
     * @var float
     *
     * @ORM\Column(name="`limit`", type="float")
     */
    private $limit;

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
     * @param Tournament|null $tournament
     *
     * @return TournamentMetricCondition
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
     * @param Metric|null $metric
     *
     * @return TournamentMetricCondition
     */
    public function setMetric(Metric $metric = null)
    {
        $this->metric = $metric;

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
     * @param Department $department
     *
     * @return TournamentMetricCondition
     */
    public function setDepartment(Department $department)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return Department
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @return float
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param float $limit
     *
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }
}
