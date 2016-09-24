<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 22.09.16
 * Time: 15:53.
 */
namespace AppBundle\Security;

use AppBundle\Exceptions\AccessTokenInvalidException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

class TokenAuthenticator implements SimplePreAuthenticatorInterface
{
    /**
     * @param Request $request
     * @param $providerKey
     *
     * @throws AccessTokenInvalidException
     *
     * @return PreAuthenticatedToken
     */
    public function createToken(Request $request, $providerKey)
    {
//        $accessToken = 'TOKEN token=token';
        $accessToken = $request->headers->get('Authorization');
        if (!$accessToken) {
            throw new AccessTokenInvalidException();
        }

        if (preg_match('/(Token|TOKEN) token="?.+"?/', $accessToken)) {
            $accessToken = trim(substr($accessToken, 12), '"');
        }

        return new PreAuthenticatedToken(
            'anon.',
            $accessToken,
            $providerKey
        );
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof DatabaseUserProvider) {
            throw new \InvalidArgumentException();
        }

        $accessToken = $token->getCredentials();
        $user = $userProvider->getUserForAccessToken($accessToken);

        if (!$user) {
            throw new AccessTokenInvalidException();
        }

        $user->setRequestToken($accessToken);

        return new PreAuthenticatedToken(
            $user,
            $accessToken,
            $providerKey,
            $user->getRoles()
        );
    }

    /**
     * @param TokenInterface $token
     * @param $providerKey
     *
     * @return bool
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }
}
