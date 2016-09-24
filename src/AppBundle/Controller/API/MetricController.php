<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 24.09.16
 * Time: 13:10.
 */
namespace AppBundle\Controller\API;

use AppBundle\Classes\Payload;
use AppBundle\Controller\BaseAPIController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Routing\ClassResourceInterface;

class MetricController extends BaseAPIController implements ClassResourceInterface
{
    /**
     * @Get("/metrics")
     */
    public function cgetAction()
    {
        $metrics = $this->get('app.manager.metric')->findAll();

        return $this->response(Payload::create($metrics));
    }
}
