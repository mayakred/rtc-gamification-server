<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    /**
     * @Route("/events")
     * @Template
     */
    public function eventsAction()
    {
        $users = $this->get('app.manager.user')->findAllOrderByTopPosition();

        return ['users' => $users];
    }
}
