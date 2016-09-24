<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 24.09.16
 * Time: 13:31.
 */
namespace AppBundle\Event;

use AppBundle\Entity\Duel;
use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class PushEvent extends Event
{
    const NAME = 'app.event.push';

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var Duel
     */
    protected $duel;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    protected $type;

    /**
     * PushEvent constructor.
     *
     * @param $title
     * @param $content
     * @param $user
     * @param $type
     */
    public function __construct($title = '', $content = '', $user, $type, $duel)
    {
        $this->title = $title;
        $this->content = $content;
        $this->user = $user;
        $this->type = $type;
        $this->duel = $duel;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

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
     * @param User $user
     *
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Duel
     */
    public function getDuel()
    {
        return $this->duel;
    }

    /**
     * @param Duel $duel
     *
     * @return $this
     */
    public function setDuel($duel)
    {
        $this->duel = $duel;

        return $this;
    }
}
