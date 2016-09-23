<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 23.09.16
 * Time: 11:19.
 */
namespace AppBundle\Controller\API;

use AppBundle\Classes\Payload;
use AppBundle\Controller\BaseAPIController;
use AppBundle\Form\Type\ConfirmationType;
use AppBundle\Form\Type\PhoneType;
use AppBundle\Model\Confirmation;
use AppBundle\Model\Phone;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends BaseAPIController implements ClassResourceInterface
{
    /**
     * @Post("/auth/request")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postAuthRequestAction(Request $request)
    {
        /**
         * @var Phone $phoneModel
         */
        $phoneModel = $this->handleJSONForm($request, $this->createForm(PhoneType::class));
        $secret = $this->get('app.handler.auth')->request($phoneModel->getPhone());

        return $this->response(Payload::create(['secret' => $secret]));
    }

    /**
     * @Post("/auth/confirm")
     *
     * @param Request $request
     *
     * @throws \AppBundle\Exceptions\CredentialsInvalidException
     * @throws \AppBundle\Exceptions\NotFoundException
     * @throws \AppBundle\Exceptions\RequestExpiredException
     * @throws \AppBundle\Exceptions\RequestRequiredException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postAuthConfirmAction(Request $request)
    {
        /**
         * @var Confirmation $confirmation
         */
        $confirmation = $this->handleJSONForm($request, $this->createForm(ConfirmationType::class));
        $accessToken = $this->get('app.handler.auth')->confirm(
            $confirmation->getPhone(),
            $confirmation->getPassword(),
            $confirmation->getPlatform(),
            $confirmation->getDeviceId()
        );
        $payload = Payload::create([
            'id' => $accessToken->getUser()->getId(),
            'access_token' => $accessToken->getToken(),
        ]);

        return $this->response($payload);
    }
}
