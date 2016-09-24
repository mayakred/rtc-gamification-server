<?php

namespace AppBundle\Controller\API;

use AppBundle\Classes\Payload;
use AppBundle\Controller\BaseAPIController;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TournamentController extends BaseAPIController implements ClassResourceInterface
{
    /**
     * @FOSRest\Get("/users/{slug}/tournaments/active")
     *
     * @param Request $request
     * @param string  $slug
     *
     * @throws NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction(Request $request, $slug)
    {
        $userId = $slug === 'me' ? $this->getUser()->getId() : (int) $slug;

        return $this->response(Payload::create([
        ]));
    }
}
