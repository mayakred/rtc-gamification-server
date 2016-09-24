<?php

namespace AppBundle\Controller\API;

use AppBundle\Classes\Payload;
use AppBundle\Controller\BaseAPIController;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TournamentController extends BaseAPIController implements ClassResourceInterface
{
    /**
     * @FOSRest\Get("/users/{slug}/tournaments/active")
     *
     * @param string $slug
     *
     * @throws NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cgetAction($slug)
    {
        $userId = $slug === 'me' ? $this->getUser()->getId() : (int) $slug;
        /** @var User|null $user */
        $user = $this->get('app.manager.user')->find($userId);
        if ($user === null) {
            throw $this->createNotFoundException();
        }

        $tournaments = $this->get('app.manager.tournament')->findActiveByUser($user);

        return $this->response(Payload::create([$tournaments]), [Tournament::SHORT_CARD]);
    }
}
