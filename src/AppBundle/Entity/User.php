<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 13.09.16
 * Time: 16:23.
 */
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User.
 *
 * @ORM\Entity()
 * @ORM\Table(name="app__users")
 */
class User extends TimestampableEntity implements UserInterface, EquatableInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
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
     */
    protected $requestToken;

    public function __construct()
    {
        $this->phones = new ArrayCollection();
        $this->accessTokens = new ArrayCollection();
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

    /**
     * @return bool
     */
    public function isMobileAppInstalled()
    {
        return count($this->accessTokens->toArray()) > 0;
    }
}
