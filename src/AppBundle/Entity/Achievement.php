<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Achievement.
 *
 * @ORM\Table(name="achievements__achievements")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AchievementRepository")
 * @JMS\ExclusionPolicy("all")
 */
class Achievement
{
    const PUBLIC_CARD = 'achievement__public';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Expose()
     * @JMS\Groups({Achievement::PUBLIC_CARD})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @JMS\Expose()
     * @JMS\Groups({Achievement::PUBLIC_CARD})
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="max_value", type="float")
     *
     * @JMS\Expose()
     * @JMS\Groups({Achievement::PUBLIC_CARD})
     */
    private $maxValue;

    /**
     * @var Image
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Image")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=true)
     *
     * @JMS\Expose()
     * @JMS\Groups({Achievement::PUBLIC_CARD})
     */
    private $image;

    /**
     * @var Metric
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Metric")
     * @ORM\JoinColumn(name="metric_id", referencedColumnName="id", nullable=false)
     *
     * @JMS\Expose()
     * @JMS\Groups({Achievement::PUBLIC_CARD})
     */
    private $metric;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string")
     *
     * @JMS\Expose()
     * @JMS\Groups({Achievement::PUBLIC_CARD})
     */
    private $description;

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
     * @return Achievement
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
     * Set image.
     *
     * @param Image $image
     *
     * @return Achievement
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set maxValue.
     *
     * @param float $maxValue
     *
     * @return Achievement
     */
    public function setMaxValue($maxValue)
    {
        $this->maxValue = $maxValue;

        return $this;
    }

    /**
     * Get maxValue.
     *
     * @return float
     */
    public function getMaxValue()
    {
        return $this->maxValue;
    }

    /**
     * @param Metric $metric
     *
     * @return Achievement
     */
    public function setMetric(Metric $metric)
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
     * @param UserAchievement $userAchievement
     *
     * @return bool
     */
    public function isReached(UserAchievement $userAchievement)
    {
        return $userAchievement->getValue() >= $this->maxValue;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
