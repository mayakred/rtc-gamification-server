<?php

namespace AppBundle\Manager;

use AppBundle\DBAL\Types\UnitType;
use AppBundle\Entity\Metric;

class MetricManager extends BaseEntityManager
{
    /**
     * @return Metric[]
     */
    public function findAvailableForTeamTournaments()
    {
        return $this->findBy(['availableForTeamTournaments' => true]);
    }

    /**
     * @return Metric[]
     */
    public function findAvailableForIndividualTournaments()
    {
        return $this->findBy(['availableForIndividualTournaments' => true]);
    }

    /**
     * @return Metric[]
     */
    public function findNonePercentage()
    {
        return $this->findBy(['unitType' => [UnitType::UNITS, UnitType::RUBLES]]);
    }
}
