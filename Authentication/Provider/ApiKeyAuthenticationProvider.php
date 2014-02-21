<?php

/*
 * This file is part of the Helthe API Security package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Component\Security\Api\Authentication\Provider;

use Helthe\Component\Security\Api\Authentication\Token\ApiKeyAuthenticatedToken;
use Helthe\Component\Security\Api\User\UserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

/**
 * Processes an API key authentication request.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class ApiKeyAuthenticationProvider implements AuthenticationProviderInterface
{
    /**
     * @var string
     */
    protected $providerKey;
    /**
     * @var UserCheckerInterface
     */
    protected $userChecker;
    /**
     * @var UserProviderInterface
     */
    protected $userProvider;

    /**
     * Constructor.
     *
     * @param UserProviderInterface $userProvider
     * @param UserCheckerInterface  $userChecker
     * @param string                $providerKey
     */
    public function __construct(UserProviderInterface $userProvider, UserCheckerInterface $userChecker, $providerKey)
    {
        $this->userProvider = $userProvider;
        $this->userChecker = $userChecker;
        $this->providerKey = $providerKey;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(TokenInterface $token)
    {
        if (!$this->supports($token)) {
            return null;
        }

        if (!$token->getUser()) {
            throw new BadCredentialsException('No pre-authenticated principal found in request.');
        }

        $user = $this->userProvider->loadUserByApiKey($token->getCredentials());

        $this->userChecker->checkPostAuth($user);

        $authenticatedToken = new ApiKeyAuthenticatedToken($user, $token->getCredentials(), $this->providerKey, $user->getRoles());
        $authenticatedToken->setAttributes($token->getAttributes());

        return $authenticatedToken;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof PreAuthenticatedToken && $this->providerKey === $token->getProviderKey();
    }
}
