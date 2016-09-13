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
}
