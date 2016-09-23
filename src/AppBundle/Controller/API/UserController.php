<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 23.09.16
 * Time: 11:27.
 */
namespace AppBundle\Controller\API;

use AppBundle\Classes\Payload;
use AppBundle\Controller\BaseAPIController;
use FOS\RestBundle\Routing\ClassResourceInterface;

class UserController extends BaseAPIController implements ClassResourceInterface
{
    public function getAction()
    {
        return $this->response(Payload::create([
            'access_token' => $this->getUser()->getRequestToken(),
            'id' => $this->getUser()->getId(),
        ]));
    }
}
