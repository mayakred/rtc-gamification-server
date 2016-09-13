<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 13.09.16
 * Time: 16:16.
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Device.
 *
 * @ORM\Table(name="app__devices")
 * @ORM\Entity()
 */
class Device extends TimestampableEntity
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
     * @ORM\Column(name="device_id", type="string", unique=true)
     */
    protected $deviceId;

    /**
     * @var string
     *
     * @ORM\Column(name="platform", type="PlatformType")
     */
    protected $platform;

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
    public function getDeviceId()
    {
        return $this->deviceId;
    }

    /**
     * @param string $deviceId
     *
     * @return $this
     */
    public function setDeviceId($deviceId)
    {
        $this->deviceId = $deviceId;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * @param string $platform
     *
     * @return $this
     */
    public function setPlatform($platform)
    {
        $this->platform = $platform;

        return $this;
    }
}
