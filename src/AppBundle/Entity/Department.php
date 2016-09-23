<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 23.09.16
 * Time: 19:27.
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Department.
 *
 * @ORM\Table(name="app__departments")
 * @ORM\Entity()
 *
 * @JMS\ExclusionPolicy("all")
 */
class Department
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @JMS\Expose()
     * @JMS\Groups("all")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="DepartmentType", unique=true)
     *
     * @JMS\Expose()
     * @JMS\Groups({"all"})
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     *
     * @JMS\Expose()
     * @JMS\Groups({"all"})
     */
    protected $name;

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
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
