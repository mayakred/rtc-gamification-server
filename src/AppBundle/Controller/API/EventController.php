<?php

namespace AppBundle\Controller\API;

use AppBundle\Classes\Payload;
use AppBundle\Controller\BaseAPIController;
use AppBundle\DBAL\Types\EventType;
use AppBundle\Entity\Event;
use AppBundle\Form\Type\CallEventType;
use AppBundle\Form\Type\MeetingEventType;
use AppBundle\Form\Type\SaleEventType;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventController extends BaseAPIController implements ClassResourceInterface
{
    /**
     * @var array
     */
    private static $forms = [
        EventType::CALL    => CallEventType::class,
        EventType::SALE    => SaleEventType::class,
        EventType::MEETING => MeetingEventType::class,
    ];

    /**
     * @FOSRest\Post("/events/{type}", requirements={"type" = "sale|meeting|call"})
     *
     * @param Request $request
     * @param string  $type
     *
     * @throws NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request, $type)
    {
        /** @var Event $event */
        $event = $this->handleJSONForm($request, $this->createForm(static::$forms[$type]));

        if (($user = $this->get('app.manager.user')->findOneByActivePhone($event->getPhone())) !== null) {
            throw $this->createNotFoundException();
        }

        $event->setUser($user);

        $this->get('app.handler.event')->add($event);

        return $this->response(Payload::create([
            $event,
        ]));
    }
}
