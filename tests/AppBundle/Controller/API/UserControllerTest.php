<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 23.09.16
 * Time: 11:29
 */

namespace tests\AppBundle\Controller\API;


use AppBundle\Entity\AccessToken;
use AppBundle\Entity\User;
use Tests\BaseControllerTestCase;

class UserControllerTest extends BaseControllerTestCase
{
    public static function setupBeforeClass()
    {
        parent::setUpBeforeClass();
        static::$baseFixturesPath = '@AppBundle/DataFixtures/ORM/Tests/Controller/API/UserController';
    }

    public function testGetActionAnonymous()
    {
        $this->loadTestBasedFixture('get_action_anonymous.yml');

        $response = $this->request('/api/mobile/v1/user');
        $this->assertAccessTokenInvalid($response, 'Access token not invalid!');
    }

    public function testGetActionSuccess()
    {
        $this->loadTestBasedFixture('get_action_success.yml');
        /**
         * @var AccessToken $accessToken
         * @var User $user
         */
        $accessToken = $this->fixtures['access_token'];
        $user = $this->fixtures['user'];

        $response = $this->request('/api/mobile/v1/user', 'GET', [], [], $accessToken->getToken());
        $this->assertAccessTokenNotInvalid($response, 'Access token is invalid!');
        $data = $this->extractJsonData($response);
        $this->assertNotNull($data['access_token'], 'data->access_token is null!');
        $this->assertEquals($data['access_token'], $accessToken->getToken());
        $this->assertNotNull($data['id'], 'data->id is null!');
        $this->assertEquals($data['id'], $user->getId());
    }

    public function testPostPlayerId()
    {
        $this->loadTestBasedFixture('post_player_id_success.yml');
        /**
         * @var AccessToken $accessToken
         */
        $accessToken = $this->fixtures['access_token'];

        $response = $this->request(
            '/api/mobile/v1/users/me/players',
            'POST',
            [],
            ['player_id' => 'playerId'],
            $accessToken->getToken()
        );
        $this->assertAccessTokenNotInvalid($response);
    }
}