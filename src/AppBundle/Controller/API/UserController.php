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
use AppBundle\DBAL\Types\PushType;
use AppBundle\DBAL\Types\TournamentType;
use AppBundle\DBAL\Types\UnitType;
use AppBundle\Entity\Achievement;
use AppBundle\Entity\Duel;
use AppBundle\Entity\Metric;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use AppBundle\Entity\UserAchievement;
use AppBundle\Event\PushEvent;
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

        return $this->response(Payload::create($user), [
            User::FULL_CARD,
            Achievement::PUBLIC_CARD,
            UserAchievement::PUBLIC_CARD,
        ]);
    }

    /**
     * @Get("/users")
     */
    public function cgetAction()
    {
        $users = $this->get('app.manager.user')->findAllOrderByTopPosition();

        return $this->response(Payload::create($users), [User::SHORT_CARD, Achievement::PUBLIC_CARD, UserAchievement::PUBLIC_CARD]);
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

        return $this->response(Payload::create($duels), [User::SHORT_CARD, Duel::FULL_CARD, Achievement::PUBLIC_CARD, UserAchievement::PUBLIC_CARD]);
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

        $this->get('event_dispatcher')->dispatch(
            PushEvent::NAME,
            new PushEvent(
                '',
                '',
                $duel->getInitiator(),
                PushType::DUEL_STARTED,
                $duel
            )
        );

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

        $this->get('event_dispatcher')->dispatch(
            PushEvent::NAME,
            new PushEvent(
                '',
                '',
                $duel->getInitiator(),
                PushType::DUEL_REJECTED,
                $duel
            )
        );

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

        $this->get('event_dispatcher')->dispatch(
            PushEvent::NAME,
            new PushEvent(
                '',
                '',
                $duel->getVictim(),
                PushType::DUEL_CREATED,
                $duel
            )
        );

        return $this->response(Payload::create($duel), [User::SHORT_CARD, Duel::FULL_CARD, Achievement::PUBLIC_CARD, UserAchievement::PUBLIC_CARD]);
    }

    /**
     * @Get("/users/{id}/tab1/statistic")
     *
     * @param $id
     *
     * @throws NotFoundException
     *
     * @return Response
     */
    public function getActiveTournamentsStatisticAction($id)
    {
        if ($id === 'me') {
            $id = $this->getUser()->getId();
        }
        /**
         * @var User $user
         */
        $user = $this->get('app.manager.user')->find($id);
        if (!$user) {
            throw new NotFoundException();
        }
        $activeTournament = $this
            ->get('app.manager.tournament')
            ->findActiveByTypeAndUser($user, TournamentType::INDIVIDUAL);
        $teams = $activeTournament->getTeams();
        $participant = $this
            ->get('app.manager.tournament_team_participant')
            ->findOneByTournamentAndUser($activeTournament, $user);
        $result = [];
        foreach ($participant->getValues() as $participantValue) {
            //find max metric value
            $winnerValue = 0;
            $teamValueFloat = 0;
            foreach ($teams as $team) {
                foreach ($team->getValues() as $teamValue) {
                    if ($teamValue->getMetric()->getCode() === $participantValue->getMetric()->getCode()) {
                        $winnerValue = max($winnerValue, $teamValue->getValue());
                        if ($team->getId() === $participant->getTeam()->getId()) {
                            $teamValueFloat = $teamValue->getValue();
                        }
                    }
                }
            }
            $threshold = null;
            foreach ($activeTournament->getMetricConditions() as $metricCondition) {
                if ($metricCondition->getMetric()->getCode() === $participantValue->getMetric()->getCode()) {
                    $threshold = $metricCondition->getLimit();
                }
            }
            $isPercent = $participantValue->getMetric()->getUnitType() === UnitType::PERCENT && $threshold !== null;
            $result[] = [
                'id' => $participantValue->getId(),
                'participant_value' => $isPercent ? $participantValue->getValue() / $threshold * 100 : $participantValue->getValue(),
                'metric' => $participantValue->getMetric(),
                'is_perviy_subview' => true, //sic(!)
                'is_vtoroy_subview' => false, //sic(!) x2
                'department' => $user->getDepartment(), //sic(!) x3
                'winner_value' => $isPercent ? $winnerValue / $threshold * 100 : $winnerValue,
                'team_value' => $isPercent ? $teamValueFloat / $threshold * 100 : $teamValueFloat,
                'user_id' => $user->getId(), //sic(!) x4
                'threshold_value' => $threshold,
            ];
        }
        $activeTournament = $this
            ->get('app.manager.tournament')
            ->findActiveByTypeAndUser($user, TournamentType::TEAM);
        $teams = $activeTournament->getTeams();
        $participant = $this
            ->get('app.manager.tournament_team_participant')
            ->findOneByTournamentAndUser($activeTournament, $user);
        foreach ($participant->getValues() as $participantValue) {
            //find max metric value
            $winnerValue = 0;
            $teamValueFloat = 0;
            foreach ($teams as $team) {
                foreach ($team->getValues() as $teamValue) {
                    if ($teamValue->getMetric()->getCode() === $participantValue->getMetric()->getCode()) {
                        $winnerValue = max($winnerValue, $teamValue->getValue());
                        if ($team->getId() === $participant->getTeam()->getId()) {
                            $teamValueFloat = $teamValue->getValue();
                        }
                    }
                }
            }
            $threshold = null;
            foreach ($activeTournament->getMetricConditions() as $metricCondition) {
                if ($metricCondition->getMetric()->getCode() === $participantValue->getMetric()->getCode()) {
                    $threshold = $metricCondition->getLimit();
                }
            }
            $isPercent = $participantValue->getMetric()->getUnitType() === UnitType::PERCENT && $threshold !== null;
            $result[] = [
                'id' => $participantValue->getId(),
                'participant_value' => $isPercent ? $participantValue->getValue() / $threshold * 100 : $participantValue->getValue(),
                'metric' => $participantValue->getMetric(),
                'is_perviy_subview' => false, //sic(!)
                'is_vtoroy_subview' => true, //sic(!) x2
                'department' => $user->getDepartment(), //sic(!) x3
                'winner_value' => $isPercent ? $winnerValue / $threshold * 100 : $winnerValue,
                'team_value' => $isPercent ? $teamValueFloat / $threshold * 100 : $teamValueFloat,
                'user_id' => $user->getId(), //sic(!) x4
                'threshold_value' => $threshold,
            ];
        }

        return $this->response(Payload::create($result));
    }

    /**
     * @param Request $request
     * @param $code
     *
     * @Get("/{code}/statistic")
     *
     * @throws NotFoundException
     *
     * @return Response
     */
    public function getParticipantsByMetricAndTournamentAction(Request $request, $code)
    {
        $metric = $this->get('app.manager.metric')->findOneBy(['code' => $code]);
        if (!$metric) {
            throw new NotFoundException();
        }
        $user = $this->getUser();
        $isIndividualTournament = boolval($request->query->get('is_perviy_subview', true));
        $activeTournament = $this
            ->get('app.manager.tournament')
            ->findActiveByTypeAndUser($user, $isIndividualTournament ? TournamentType::INDIVIDUAL : TournamentType::TEAM);
        $participants = $this
            ->get('app.manager.tournament_team_participant')
            ->findByTournament($activeTournament);
        $result = [];
        foreach ($participants as $participant) {
            foreach ($participant->getValues() as $value) {
                if ($value->getMetric()->getCode() === $code) {
                    $result[] = [
                        'id' => $participant->getId(),
                        'metric' => $value->getMetric(),
                        'metric_value' => $value->getValue(),
                        'user_name' => $participant->getUser()->getFullName(),
                        'is_perviy_subview' => $isIndividualTournament,
                        'is_vtoroy_subview' => !$isIndividualTournament,
                        'user_avatar' => $participant->getUser()->getAvatar(),
                        'user_department' => $participant->getUser()->getDepartment(),
                    ];
                }
            }
        }

        return $this->response(Payload::create($result));
    }
}
