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
use AppBundle\DBAL\Types\DuelStatusType;
use AppBundle\Entity\Duel;
use AppBundle\Entity\Metric;
use AppBundle\Entity\User;
use AppBundle\Exceptions\ActionNotAllowedException;
use AppBundle\Exceptions\FormInvalidException;
use AppBundle\Exceptions\NotFoundException;
use AppBundle\Form\Type\DuelType;
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

    /**
     * @Get("/users/{slug}/duels")
     */
    public function cgetDuelAction()
    {
        $duels = $this->get('app.manager.duel')->findAllRelatedToUser($this->getUser());

        return $this->response(Payload::create($duels), [User::SHORT_CARD, Duel::FULL_CARD]);
    }

    /**
     * @param $duelId
     *
     * @throws ActionNotAllowedException
     * @throws NotFoundException
     *
     * @return Response
     *
     * @Post("/users/me/duels/{duelId}/accept")
     */
    public function postAcceptDuelAction($duelId)
    {
        /**
         * @var Duel $duel
         */
        $duel = $this->get('app.manager.duel')->find($duelId);
        if (!$duel) {
            throw new NotFoundException();
        }
        if ($duel->getVictim() !== $this->getUser()) {
            throw new ActionNotAllowedException();
        }
        if ($duel->getStatus() !== DuelStatusType::WAITING_VICTIM) {
            throw new ActionNotAllowedException();
        }
        $duel->setStatus(DuelStatusType::IN_PROGRESS);
        $duel->setSince(new \DateTime(null, new \DateTimeZone('UTC')));
        $this->get('app.manager.duel')->save($duel);
        //TODO: Send PUSH

        return $this->response(Payload::create());
    }

    /**
     * @param $duelId
     *
     * @throws ActionNotAllowedException
     * @throws NotFoundException
     *
     * @return Response
     *
     * @Post("/users/me/duels/{duelId}/reject")
     */
    public function postRejectDuelAction($duelId)
    {
        /**
         * @var Duel $duel
         */
        $duel = $this->get('app.manager.duel')->find($duelId);
        if (!$duel) {
            throw new NotFoundException();
        }
        if ($duel->getVictim() !== $this->getUser()) {
            throw new ActionNotAllowedException();
        }
        if ($duel->getStatus() !== DuelStatusType::WAITING_VICTIM) {
            throw new ActionNotAllowedException();
        }
        $duel->setStatus(DuelStatusType::REJECTED_BY_VICTIM);
        $duel->setSince(new \DateTime(null, new \DateTimeZone('UTC')));
        $duel->setUntil($duel->getSince());
        $this->get('app.manager.duel')->save($duel);
        //TODO: Send PUSH

        return $this->response(Payload::create());
    }

    /**
     * @param Request $request
     *
     * @throws FormInvalidException
     *
     * @return Response
     *
     * @Post("/users/me/duels")
     */
    public function postDuelAction(Request $request)
    {
        //OH MY GOD<<<
        //>>>SOME SHITTY CODE
        //>NO MODEL VALIDATIONS<<
        //>>>>>NO DATA TRANSFORMERS<<<<
        //I'M SORRY...
        $duelData = $this->handleJSONForm($request, $this->createForm(DuelType::class));
        /**
         * @var User   $victim
         * @var Metric $metric
         */
        $victim = $this->get('app.manager.user')->find($duelData['victim_id']);
        $metric = $this->get('app.manager.metric')->findOneBy([
            'code' => $duelData['metric_code'],
            'availableForDuel' => true,
        ]);
        $until = new \DateTime(null, new \DateTimeZone('UTC'));
        $until->setTimestamp($duelData['end_at']);
        $currentDt = new \DateTime(null, new \DateTimeZone('UTC'));
        if (!$victim || !$metric || !$until || $until < $currentDt) {
            throw new FormInvalidException();
        }
        $duel = new Duel();
        $duel
            ->setSince(new \DateTime(null, new \DateTimeZone('UTC')))
            ->setUntil($until)
            ->setVictim($victim)
            ->setMetric($metric)
            ->setInitiator($this->getUser())
            ->setStatus(DuelStatusType::WAITING_VICTIM);
        $this->get('app.manager.duel')->save($duel);
        //TODO: Send push

        return $this->response(Payload::create($duel), [User::SHORT_CARD, Duel::FULL_CARD]);
    }
}
