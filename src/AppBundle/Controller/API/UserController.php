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
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends BaseAPIController implements ClassResourceInterface
{
    /**
     * @Get("/users/{slug}")
     *
     * @param string $slug
     *
     * @return Response
     */
    public function getAction($slug)
    {
        $userId = $slug === 'me' ? $this->getUser()->getId() : intval($slug);
        $user = $this->get('app.manager.user')->find($userId);

        return $this->response(Payload::create($user), [User::FULL_CARD]);
    }

    /**
     * @Get("/users")
     */
    public function cgetAction()
    {
        $users = $this->get('app.manager.user')->findAllOrderByTopPosition();

        return $this->response(Payload::create($users), [User::SHORT_CARD]);
    }

    /**
     * @param Request $request
     * @param $slug
     *
     * @Post("/users/{slug}/players")
     *
     * @return Response
     */
    public function postPlayerAction(Request $request, $slug)
    {
        /**
         * @var string $playerId
         */
        $playerId = $this->handleJSONForm($request, $this->createForm(TextType::class))['player_id'];
        $this->get('app.handler.user')->addPlayerId($playerId, $this->getUser());

        return $this->response(Payload::create());
    }
}
