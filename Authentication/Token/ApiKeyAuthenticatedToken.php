<?php

/*
 * This file is part of the Helthe API Security package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Component\Security\Api\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;

/**
 * ApiKeyAuthenticatedToken represents a pre-authenticated token that was authenticated using an API key.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class ApiKeyAuthenticatedToken extends PreAuthenticatedToken
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * Constructor.
     *
     * @param mixed  $user
     * @param string $credentials
     * @param string $providerKey
     * @param array  $roles
     */
    public function __construct($user, $credentials, $providerKey, array $roles = array())
    {
        parent::__construct($user, $credentials, $providerKey, $roles);

        $this->apiKey = $credentials;
    }

    /**
     * Get the API key used to authenticate the user.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }
}
