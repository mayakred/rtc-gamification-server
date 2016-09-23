<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 23.09.16
 * Time: 13:48.
 */
namespace AppBundle\Model;

use AppBundle\Model\Partial\PhonePartial;
use AppBundle\Model\Partial\PhonePartialInterface;
use AppBundle\Model\Partial\PlatformPartial;
use AppBundle\Model\Partial\PlatformPartialInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Confirmation implements PhonePartialInterface, PlatformPartialInterface
{
    use PhonePartial;
    use PlatformPartial;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="IsEmpty")
     */
    protected $password;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="IsEmpty")
     */
    protected $deviceId;

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

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
}
