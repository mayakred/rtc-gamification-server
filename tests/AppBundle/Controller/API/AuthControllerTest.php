<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 23.09.16
 * Time: 13:34
 */

namespace tests\AppBundle\Controller\API;


use AppBundle\Entity\Phone;
use AppBundle\Entity\User;
use Tests\BaseControllerTestCase;

class AuthControllerTest extends BaseControllerTestCase
{
    public static function setupBeforeClass()
    {
        parent::setUpBeforeClass();
        static::$baseFixturesPath = '@AppBundle/DataFixtures/ORM/Tests/Controller/API/AuthController';
    }

    public function testAuthRequestAnonymous()
    {
        $response = $this->postJSONForm('/api/mobile/v1/auth/request');
        $this->assertAccessTokenNotInvalid($response);
    }

    public function testAuthRequestWithoutPhone()
    {
        $response = $this->postJSONForm('/api/mobile/v1/auth/request', []);
        $this->assertJsonResponse($response, 400);
        $this->assertErrorCode($response, 'FormInvalid');
        $this->assertHasErrorsKey($response, 'phone');
    }

    public function testAuthRequestWithPhone()
    {
        $response = $this->postJSONForm('/api/mobile/v1/auth/request', ['phone' => '+70000000000']);
        $this->assertJsonResponse($response, 200);
        $data = $this->extractJsonData($response);
        $this->assertArrayHasKey('secret', $data);
        $this->assertNotNull($data['secret']);
    }

    public function testAuthConfirmSuccess()
    {
        $this->loadTestBasedFixture('auth_confirm_success.yml');
        /**
         * @var User $user
         * @var Phone $phone
         */
        $user = $this->fixtures['user'];
        $phone = $this->fixtures['phone'];
        $password = hash('sha256', $user->getSmsCode() . $user->getSecret());

        $response = $this->postJSONForm('/api/mobile/v1/auth/confirm', [
            'phone' => $phone->getPhone  (),
            'password' => $password,
            'device_id' => 'device_id',
            'platform' => 'ios',
        ]);
        $this->assertJsonResponse($response, 200);
        $data = $this->extractJsonData($response);
        $this->assertArrayHasKey('access_token', $data);
//        $response = $this->request('/api/mobile/v1/users/me', 'GET', [], [], $data['access_token']);
//        $this->assertJsonResponse($response, 200);
//        $data = $this->extractJsonData($response);
    }
}