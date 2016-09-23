<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 23.09.16
 * Time: 13:19
 */

namespace tests\AppBundle\Handler;


use AppBundle\Entity\Phone;
use AppBundle\Entity\User;
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
}