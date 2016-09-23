<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 23.09.16
 * Time: 13:19
 */

namespace tests\AppBundle\Handler;


use AppBundle\DBAL\Types\PlatformType;
use AppBundle\Entity\Phone;
use AppBundle\Entity\User;
use AppBundle\Exceptions\CredentialsInvalidException;
use AppBundle\Exceptions\NotFoundException;
use AppBundle\Exceptions\RequestExpiredException;
use AppBundle\Exceptions\RequestRequiredException;
use AppBundle\Handler\AuthHandler;
use Tests\BaseServiceTestCase;

class AuthHandlerTest extends BaseServiceTestCase
{
    /**
     * @var AuthHandler
     */
    protected static $authHandler;

    public static function setupBeforeClass()
    {
        parent::setUpBeforeClass();
        static::$authHandler = static::$container->get('app.handler.auth');
        static::$baseFixturesPath = '@AppBundle/DataFixtures/ORM/Tests/Handler/AuthHandler';
    }

    public function testRequestExistedUser()
    {
        $this->loadTestBasedFixture('request_existed_user.yml');
        /**
         * @var Phone $phone
         * @var User $existedUser
         */
        $phone = $this->fixtures['phone'];
        $existedUser = $this->fixtures['user'];
        $secret = static::$authHandler->request($phone->getPhone());
        $this->assertNotNull($secret, 'Secret is null');
        $existedUser = static::$container->get('doctrine.orm.entity_manager')->getRepository('AppBundle:User')->find($existedUser->getId());
        $this->assertNotNull($existedUser, 'Can\'t find user!');
        $this->assertNotNull($existedUser->getSmsCode(), 'Sms code is null!');
        $this->assertNotNull($existedUser->getSmsCodeDt(), 'Sms code datetime is null!');
        $this->assertNotNull($existedUser->getSecret(), 'Secret is null!');
        $this->assertEquals($existedUser->getSecret(), $secret, 'Secrets are not the same');
    }

    public function testRequestNewUser()
    {
        $this->loadTestBasedFixture();
        $phone = '+70000000000';
        $secret = static::$authHandler->request($phone);
        $this->assertNotNull($secret, 'Secret is null');
        /**
         * @var User[] $users
         */
        $users = static::$container->get('doctrine.orm.entity_manager')->getRepository('AppBundle:User')->findAll();
        $this->assertCount(1, $users, 'Users count not equal 1');
        $user = $users[0];
        $this->assertNotNull($user, 'Can\'t find user!');
        $this->assertNotNull($user->getSmsCode(), 'Sms code is null!');
        $this->assertNotNull($user->getSmsCodeDt(), 'Sms code datetime is null!');
        $this->assertNotNull($user->getSecret(), 'Secret is null!');
        $this->assertEquals($user->getSecret(), $secret, 'Secrets are not the same');
    }

    public function testConfirmNoUser()
    {
        $this->loadTestBasedFixture();
        try {
            static::$authHandler->confirm('+70000000000', 'pass', PlatformType::IOS, 'device_id');
            $this->fail('No exception was thrown');
        } catch (NotFoundException $e) {
        }
    }

    public function testConfirmRequestRequired()
    {
        $this->loadTestBasedFixture('confirm_request_required.yml');
        /**
         * @var Phone $phone
         */
        $phone = $this->fixtures['phone'];
        try {
            static::$authHandler->confirm($phone->getPhone(), 'pass', PlatformType::IOS, 'device_id');
            $this->fail('No exception was thrown');
        } catch (RequestRequiredException $e) {
        }
    }

    public function testConfirmRequestExpired()
    {
        $this->loadTestBasedFixture('confirm_request_expired.yml');
        /**
         * @var Phone $phone
         */
        $phone = $this->fixtures['phone'];
        try {
            static::$authHandler->confirm($phone->getPhone(), 'pass', PlatformType::IOS, 'device_id');
            $this->fail('No exception was thrown');
        } catch (RequestExpiredException $e) {
        }
    }

    public function testConfirmInvalidCredentials()
    {
        $this->loadTestBasedFixture('confirm_invalid_credentials.yml');
        /**
         * @var Phone $phone
         * @var User $user
         */
        $phone = $this->fixtures['phone'];
        $user = $this->fixtures['user'];
        $password = hash('sha256', $user->getSmsCode() . $user->getSecret()) . 'error';
        try {
            static::$authHandler->confirm($phone->getPhone(), $password, PlatformType::IOS, 'device_id');
            $this->fail('No exception was thrown');
        } catch (CredentialsInvalidException $e) {
        }
    }

    public function testConfirmSuccess()
    {
        $this->loadTestBasedFixture('confirm_success.yml');
        /**
         * @var Phone $phone
         * @var User $user
         */
        $phone = $this->fixtures['phone'];
        $user = $this->fixtures['user'];
        $password = hash('sha256', $user->getSmsCode() . $user->getSecret());
        $result = static::$authHandler->confirm($phone->getPhone(), $password, PlatformType::IOS, 'device_id');
        $this->assertNotNull($result);
    }
}