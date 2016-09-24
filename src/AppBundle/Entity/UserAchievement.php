<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Achievement.
 *
 * @ORM\Table(name="achievements__achievements__users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AchievementRepository")
 * @JMS\ExclusionPolicy("all")
 */
class UserAchievement
{
    const PUBLIC_CARD = 'achievement__users_public';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Expose()
     * @JMS\Groups({UserAchievement::PUBLIC_CARD})
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="userAchievements")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $user;

    /**
     * @var Achievement
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Achievement")
     * @ORM\JoinColumn(name="achievement_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @JMS\Expose()
     * @JMS\Groups({UserAchievement::PUBLIC_CARD})
     */
    private $achievement;

    /**
     * @var float
     *
     * @ORM\Column(name="value", type="float")
     *
     * @JMS\Expose()
     * @JMS\Groups({UserAchievement::PUBLIC_CARD})
     * @JMS\SerializedName("current_value")
     */
    private $value;

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
     * @param User $user
     *
     * @return UserAchievement
     */
    public function setUser(User $user)
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
     * @param Achievement $achievement
     *
     * @return UserAchievement
     */
    public function setAchievement(Achievement $achievement)
    {
        $this->achievement = $achievement;

        return $this;
    }

    /**
     * @return Achievement
     */
    public function getAchievement()
    {
        return $this->achievement;
    }

    /**
     * Set value.
     *
     * @param float $value
     *
     * @return Achievement
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param int $value
     *
     * @return UserAchievement
     */
    public function addValue($value)
    {
        $this->value += $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }
}
