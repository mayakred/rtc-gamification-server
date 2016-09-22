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

/**
 * Class User.
 *
 * @ORM\Entity()
 * @ORM\Table(name="app__users")
 */
class User extends TimestampableEntity
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
}
