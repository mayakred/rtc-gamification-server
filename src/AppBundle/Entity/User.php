<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 13.09.16
 * Time: 16:23.
 */
namespace AppBundle\Entity;

use AppBundle\DBAL\Types\GenderType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User.
 *
 * @ORM\Entity()
 * @ORM\Table(name="app__users")
 *
 * @JMS\ExclusionPolicy("all")
 */
class User extends TimestampableEntity implements UserInterface, EquatableInterface
{
    const FULL_CARD = 'user__full';
    const SHORT_CARD = 'user__short';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @JMS\Expose()
     * @JMS\Groups({User::FULL_CARD, User::SHORT_CARD})
     */
    protected $id;

    /**
     * @var Phone[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Phone",
     *     mappedBy="user",
     *     cascade={"remove", "persist"},
     *     orphanRemoval=true
     * )
     * @ORM\OrderBy({"id"= "ASC"})
     */
    protected $phones;

    /**
     * @var AccessToken[]
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\AccessToken",
     *     mappedBy="user",
     *     cascade={"remove", "persist"},
     *     orphanRemoval=true
     * )
     */
    protected $accessTokens;

    /**
     * @var string
     *
     * @ORM\Column(name="secret", type="string", nullable=true)
     */
    protected $secret;

    /**
     * @var string
     *
     * @ORM\Column(name="sms_code", type="string", nullable=true)
     */
    protected $smsCode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sms_code_dt", type="datetime", nullable=true)
     */
    protected $smsCodeDt;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", nullable=true)
     *
     * @JMS\Expose()
     * @JMS\Groups({User::FULL_CARD, User::SHORT_CARD})
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="second_name", type="string", nullable=true)
     *
     * @JMS\Expose()
     * @JMS\Groups({User::FULL_CARD, User::SHORT_CARD})
     */
    protected $secondName;

    /**
     * @var string
     *
     * @ORM\Column(name="middle_name", type="string", nullable=true)
     *
     * @JMS\Expose()
     * @JMS\Groups({User::FULL_CARD, User::SHORT_CARD})
     */
    protected $middleName;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="GenderType")
     *
     * @JMS\Expose()
     * @JMS\Groups({User::FULL_CARD, User::SHORT_CARD})
     */
    protected $gender;

    /**
     * @var int
     *
     * @ORM\Column(name="rating", type="integer")
     *
     * @JMS\Expose()
     * @JMS\Groups({User::FULL_CARD, User::SHORT_CARD})
     */
    protected $rating;

    /**
     * @var int
     *
     * @ORM\Column(name="top_position", type="integer")
     *
     * @JMS\Expose()
     * @JMS\Groups({USER::FULL_CARD, User::SHORT_CARD})
     */
    protected $topPosition;

    /**
     * @var Department
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Department")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id")
     *
     * @JMS\Expose()
     * @JMS\Groups({USER::FULL_CARD, User::SHORT_CARD})
     */
    protected $department;

    /**
     * @var Image
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Image")
     * @ORM\JoinColumn(name="avatar_id", referencedColumnName="id", nullable=true)
     */
    protected $avatar;

    /**
     * @var string
     */
    protected $requestToken;

    public function __construct()
    {
        $this->phones = new ArrayCollection();
        $this->accessTokens = new ArrayCollection();
        $this->rating = 0;
        $this->topPosition = 0;
        $this->gender = GenderType::MALE;
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
     * @return Phone[]|ArrayCollection
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * @param Phone[]|ArrayCollection $phones
     *
     * @return $this
     */
    public function setPhones($phones)
    {
        $this->phones = $phones;

        return $this;
    }

    /**
     * @param Phone $phone
     *
     * @return $this
     */
    public function addPhone(Phone $phone)
    {
        if (!$this->phones->contains($phone)) {
            $phone->setUser($this);
            $this->phones->add($phone);
        }

        return $this;
    }

    /**
     * @param Phone $phone
     *
     * @return $this
     */
    public function removePhone(Phone $phone)
    {
        if ($this->phones->contains($phone)) {
            $phone->setUser(null);
            $this->phones->removeElement($phone);
        }

        return $this;
    }

    /**
     * @return AccessToken[]
     */
    public function getAccessTokens()
    {
        return $this->accessTokens;
    }

    /**
     * @param AccessToken[] $accessTokens
     *
     * @return $this
     */
    public function setAccessTokens($accessTokens)
    {
        $this->accessTokens = $accessTokens;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     *
     * @return $this
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * @return string
     */
    public function getSmsCode()
    {
        return $this->smsCode;
    }

    /**
     * @param string $smsCode
     *
     * @return $this
     */
    public function setSmsCode($smsCode)
    {
        $this->smsCode = $smsCode;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSmsCodeDt()
    {
        return $this->smsCodeDt;
    }

    /**
     * @param \DateTime $smsCodeDt
     *
     * @return $this
     */
    public function setSmsCodeDt($smsCodeDt)
    {
        $this->smsCodeDt = $smsCodeDt;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecondName()
    {
        return $this->secondName;
    }

    /**
     * @param string $secondName
     *
     * @return $this
     */
    public function setSecondName($secondName)
    {
        $this->secondName = $secondName;

        return $this;
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * @param string $middleName
     *
     * @return $this
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        $result = $this->firstName;

        if ($this->middleName) {
            $result .= ' ' . $this->middleName;
        }

        if ($this->secondName) {
            $result .= ' ' . $this->secondName;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     *
     * @return $this
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     *
     * @return $this
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return int
     */
    public function getTopPosition()
    {
        return $this->topPosition;
    }

    /**
     * @param int $topPosition
     *
     * @return $this
     */
    public function setTopPosition($topPosition)
    {
        $this->topPosition = $topPosition;

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
     * @param Department $department
     *
     * @return $this
     */
    public function setDepartment($department)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return array
     *
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("avatar")
     * @JMS\Groups({User::FULL_CARD, User::SHORT_CARD})
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param Image $avatar
     *
     * @return $this
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return array
     *
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("achievements")
     * @JMS\Groups({USER::FULL_CARD, User::SHORT_CARD})
     */
    public function getAchievements()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getRequestToken()
    {
        return $this->requestToken;
    }

    /**
     * @param string $requestToken
     *
     * @return $this
     */
    public function setRequestToken($requestToken)
    {
        $this->requestToken = $requestToken;

        return $this;
    }

    //UserInterface
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getPassword()
    {
        return '';
    }

    public function getSalt()
    {
        return '';
    }

    public function getUsername()
    {
        return $this->getId();
    }

    public function eraseCredentials()
    {
    }

    //EquatableInterface
    public function isEqualTo(UserInterface $user)
    {
        return $user->getUsername() === $this->getUsername();
    }

    //Auth
    /**
     * @return bool
     */
    public function isSmsCodeExpired()
    {
        if (!$this->smsCodeDt) {
            return false;
        }
        $dt = new \DateTime(null, $this->smsCodeDt->getTimezone());

        return $dt->getTimestamp()  >= $this->smsCodeDt->getTimestamp() + 5 * 60;
    }

    /**
     * @return $this
     */
    public function clearAuthInfo()
    {
        $this->smsCode = null;
        $this->smsCodeDt = null;

        return $this;
    }

    /**
     * @param $password
     *
     * @return bool
     */
    public function checkCredentials($password)
    {
        return $password == hash('sha256', $this->smsCode . $this->secret);
    }

    //Other
    /**
     * @return Phone
     *
     * @JMS\VirtualProperty()
     * @JMS\Groups({User::FULL_CARD})
     * @JMS\SerializedName("phone")
     * @JMS\Inline()
     */
    public function getActivePhone()
    {
        $activePhone = null;
        foreach ($this->phones as $phone) {
            /**
             * @var Phone $phone
             */
            if ($phone->isActual()) {
                $activePhone = $phone;
                break;
            }
        }

        return $activePhone;
    }
}
