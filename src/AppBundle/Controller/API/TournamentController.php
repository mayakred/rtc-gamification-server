<?php

namespace AppBundle\Controller\API;

use AppBundle\Classes\Payload;
use AppBundle\Controller\BaseAPIController;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\TournamentTeam;
use AppBundle\Entity\TournamentTeamParticipant;
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
        $user = $this->getAccessedUser($slug);

        $tournaments = $this->get('app.manager.tournament')->findActiveByUser($user);

        return $this->response(Payload::create([$tournaments]), [
            Tournament::PUBLIC_CARD,
            TournamentTeam::PUBLIC_CARD,
            TournamentTeamParticipant::PUBLIC_CARD,
            User::SHORT_CARD,
        ]);
    }

    /**
     * @FOSRest\Get("/tournaments/{id}", requirements={"id" = "\d+"})
     *
     * @param string $id
     *
     * @throws NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction($id)
    {
        $tournament = $this->get('app.manager.tournament')->findFullInfo($id);

        return $this->response(Payload::create($tournament), [
            Tournament::PUBLIC_CARD,
            TournamentTeam::PUBLIC_CARD,
            TournamentTeamParticipant::PUBLIC_CARD,
            User::SHORT_CARD,
        ]);
    }

    /**
     * @FOSRest\Get("/tournaments/{id}/participants/{participantId}", requirements={
     *     "id" = "\d+",
     *     "participantId" = "\d+"
     * })
     *
     * @param string $id
     * @param string $participantId
     *
     * @throws NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getParticipantInfoAction($id, $participantId)
    {
        $participant = $this->get('app.manager.tournament')->findParticipant($id, $participantId);
        if ($participant === null) {
            throw $this->createNotFoundException();
        }

        return $this->response(Payload::create($participant), [
            TournamentTeamParticipant::INFO_CARD,
            User::SHORT_CARD,
            TournamentTeam::SHORT_CARD,
        ]);
    }

    /**
     * @param string $slug
     *
     * @return User|null
     */
    private function getAccessedUser($slug = 'me')
    {
        if ($slug === 'me') {
            $user = $this->getUser();
        } else {
            $user = $this->get('app.manager.user')->find((int) $slug);
            if ($user === null) {
                throw $this->createNotFoundException();
            }
        }

        return $user;
    }
}
