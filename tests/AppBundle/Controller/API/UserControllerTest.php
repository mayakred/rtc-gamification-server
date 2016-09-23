<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 23.09.16
 * Time: 11:29
 */

namespace tests\AppBundle\Controller\API;


use AppBundle\Entity\AccessToken;
use AppBundle\Entity\Department;
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

        $response = $this->request('/api/mobile/v1/users/me');
        $this->assertAccessTokenInvalid($response, 'Access token not invalid!');
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

    public function testGetActionSuccess()
    {
        $this->loadTestBasedFixture('get_action_success.yml');
        /**
         * @var User $user
         * @var AccessToken $accessToken
         * @var Department $department
         */
        $user = $this->fixtures['user'];
        $department = $this->fixtures['department__service'];
        $accessToken = $this->fixtures['access_token'];
        $response = $this->request('/api/mobile/v1/users/me', 'GET', [], [], $accessToken->getToken());
        $this->assertJsonResponse($response, 200);
        $data = $this->extractJsonData($response);
        $this->assertNotNull($data);
        $this->assertEquals($data['id'], $user->getId());
        $this->assertEquals($data['first_name'], $user->getFirstName());
        $this->assertEquals($data['second_name'], $user->getSecondName());
        $this->assertEquals($data['middle_name'], $user->getMiddleName());
        $this->assertEquals($data['gender'], $user->getGender());
        $this->assertEquals($data['phone'], $user->getActivePhone());
        $this->assertEquals($data['rating'], $user->getRating());
        $this->assertEquals($data['top_position'], $user->getTopPosition());
        $departmentA = $data['department'];
        $this->assertEquals($departmentA['id'], $department->getId());
        $this->assertEquals($departmentA['code'], $department->getCode());
        $this->assertEquals($departmentA['name'], $department->getName());
    }
}