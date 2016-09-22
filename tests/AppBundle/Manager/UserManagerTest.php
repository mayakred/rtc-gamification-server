<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 22.09.16
 * Time: 16:41
 */

namespace tests\AppBundle\Manager;


use AppBundle\Entity\AccessToken;
use AppBundle\Entity\User;
use AppBundle\Manager\UserManager;
use Tests\BaseServiceTestCase;

class UserManagerTest extends BaseServiceTestCase
{
    /**
     * @var UserManager
     */
    protected static $userManager;

    public static function setupBeforeClass()
    {
        parent::setUpBeforeClass();
        static::$userManager = static::$container->get('app.manager.user');
        static::$baseFixturesPath = '@AppBundle/DataFixtures/ORM/Tests/Manager/UserManager';
    }

    public function testFindOneByActiveAccessTokenWithNull()
    {
        $this->loadTestBasedFixture('find_one_by_active_access_token_with_null.yml');

        $user = self::$userManager->findOneByActiveAccessToken(null);
        $this->assertNull($user, 'User is not null!');
    }

    public function testFindOneByActiveAccessTokenWithInvalidToken()
    {
        $this->loadTestBasedFixture('find_one_by_active_access_token_with_invalid_token.yml');
        /**
         * @var AccessToken $token
         */
        $token = $this->fixtures['access_token'];
        $fakeToken = $token->getToken() . 'asdf';

        $user = self::$userManager->findOneByActiveAccessToken($fakeToken);
        $this->assertNull($user, 'User is not null!');
    }

    public function testFindOneByActiveAccessTokenWithExpiredToken()
    {
        $this->loadTestBasedFixture('find_one_by_active_access_token_with_expired_token.yml');
        /**
         * @var AccessToken $token
         */
        $token = $this->fixtures['access_token'];
        $user = self::$userManager->findOneByActiveAccessToken($token->getToken());
        $this->assertNull($user, 'User is not null!');
    }

    public function testFindOneByActiveAccessTokenSuccess()
    {
        $this->loadTestBasedFixture('find_one_by_active_access_token_success.yml');
        /**
         * @var AccessToken $token
         * @var User $realUser
         */
        $token = $this->fixtures['access_token'];
        $realUser = $this->fixtures['user'];
        $user = self::$userManager->findOneByActiveAccessToken($token->getToken());
        $this->assertNotNull($user, 'User is null!');
        $this->assertEquals($user->getId(), $realUser->getId());
    }

}