<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 23.09.16
 * Time: 12:19
 */

namespace tests\AppBundle\Handler;


use AppBundle\Entity\Phone;
use AppBundle\Entity\User;
use AppBundle\Handler\UserHandler;
use Tests\BaseServiceTestCase;

class UserHandlerTest extends BaseServiceTestCase
{
    /**
     * @var UserHandler
     */
    protected static $userHandler;

    public static function setupBeforeClass()
    {
        parent::setUpBeforeClass();
        static::$userHandler = static::$container->get('app.handler.user');
        static::$baseFixturesPath = '@AppBundle/DataFixtures/ORM/Tests/Handler/UserHandler';
    }

    public function testPrepareUserWithPhone()
    {
        $phone = '+70000000000';
        $user = static::$userHandler->prepareUserWithPhone($phone);
        $this->assertNotNull($user, 'User is null!');
        $this->assertInstanceOf(User::class, $user, 'User is not instance of User Entity!');
        $this->assertCount(1, $user->getPhones(), 'User has more than 1 phone');
        $phone = $user->getPhones()[0];
        $this->assertNotNull($phone, 'Phone is null!');
        $this->assertInstanceOf(Phone::class, $phone);
        $this->assertEquals($phone->getPhone(), $phone, 'Phones mismatch');
    }

    public function testGetOrCreateUserByPhoneUserExists()
    {
        $this->loadTestBasedFixture('get_or_create_user_by_phone_user_exists.yml');
        /**
         * @var User $existedUser
         * @var Phone $phone
         */
        $existedUser = $this->fixtures['user'];
        $phone = $this->fixtures['phone'];
        $user = static::$userHandler->getOrCreateUserByPhone($phone->getPhone());
        $this->assertNotNull($user, 'User is null!');
        $this->assertEquals($user->getId(), $existedUser->getId(), 'User not found!');
    }

    public function testGetOrCreateUserByPhoneNewUser()
    {
        $phone = '+70000000000';
        $user = static::$userHandler->getOrCreateUserByPhone($phone);
        $this->assertNotNull($user, 'User is null!');
        $this->assertEquals($user->getPhones()[0]->getPhone(), $phone, 'Phones mismatch');
    }

}