<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 23.09.16
 * Time: 19:27.
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Department.
 *
 * @ORM\Table(name="app__departments")
 * @ORM\Entity()
 */
class Department
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
     * @var string
     *
     * @ORM\Column(name="code", type="DepartmentType", unique=true)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    protected $name;
}
