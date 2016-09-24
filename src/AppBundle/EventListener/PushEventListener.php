<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 24.09.16
 * Time: 13:30.
 */
namespace AppBundle\EventListener;

use AppBundle\DBAL\Types\GenderType;
use AppBundle\DBAL\Types\PushType;
use AppBundle\Event\PushEvent;
use AppBundle\Manager\UserManager;

class PushEventListener
{
    /**
     * @var string
     */
    protected $restAPIKey;

    /**
     * @var string
     */
    protected $appId;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * PushEventListener constructor.
     *
     * @param string      $restAPIKey
     * @param string      $appId
     * @param UserManager $userManager
     */
    public function __construct($restAPIKey, $appId, $userManager)
    {
        $this->restAPIKey = $restAPIKey;
        $this->appId = $appId;
        $this->userManager = $userManager;
    }

    public function processPush(PushEvent $event)
    {
        $playerIds = [];
        $user = $this->userManager->findJoinedWithAccessTokens($event->getUser()->getId());
        foreach ($user->getAccessTokens() as $accessToken) {
            $playerId = $accessToken->getPlayerId();
            if ($playerId !== null && !in_array($playerId, $playerIds)) {
                $playerIds[] = $playerId;
            }
        }
        if (count($playerIds) === 0) {
            return;
        }

        $title = $event->getTitle();
        $content = $event->getContent();

        $data = [
            'type' => $event->getType(),
            'duel_id' => $event->getDuel()->getId(),
        ];
        switch ($event->getType()) {
            case PushType::DUEL_CREATED:
                $title = 'Вызов на дуэль!';
                $content = $user->getFullName() . ' вызывает на дуэль!';
                break;
            case PushType::DUEL_STARTED:
                $title = 'Дуэль начался!';
                $content = 'Ваш противник ' . $user->getFullName() . '!';
                break;
            case PushType::DUEL_DEFEATED:
                $title = 'Дуэль проигран! =(';
                $content = $user->getFullName() . ' победил' . ($user->getGender() === GenderType::MALE ? '' : 'а') . '!';
                break;
            case PushType::DUEL_REJECTED:
                $title = 'Дуэль отклонен!';
                $content = 'Соперник ' . $user->getFullName() . ' отменил' . ($user->getGender() === GenderType::MALE ? '' : 'а') . ' дуэль!';
                break;
            case PushType::DUEL_WON:
                $title = 'Дуэль выигран!';
                $content = 'Вы победили!';
                break;
            case PushType::EVENT_REACHED:
                $title = 'Получено достижение!';
                $content = 'Вы получили достижение "' . $event->getAchievement()->getName() . '"!';
                break;
        }

        $data = [
            'app_id' => $this->appId,
            'contents' => [
                'en' => $content,
            ],
            'headings' => [
                'en' => $title,
            ],
            'data' => $data,
            'include_player_ids' => $playerIds,
        ];
        $dataString = json_encode($data);
        $length = mb_strlen($dataString, 'UTF-8');
        $headers = [
            'Content-Type: application/json',
            "Authorization: Basic {$this->restAPIKey}",
            "Content-Length: $length",
        ];

        $resource = curl_init();
        curl_setopt($resource, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
        curl_setopt($resource, CURLOPT_POST, true);
        curl_setopt($resource, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($resource, CURLOPT_POSTFIELDS, $dataString);
        curl_exec($resource);
        curl_close($resource);
    }
}
