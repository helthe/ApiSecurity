<?php

/*
 * This file is part of the Helthe API Security package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Component\Security\Api\User;

use Helthe\Component\Security\Api\Exception\ApiKeyNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Represents a class that loads UserInterface objects using an API key for the authentication system.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
interface UserProviderInterface
{
    /**
     * Loads the user for the given API key.
     *
     * @param string $apiKey
     *
     * @return UserInterface
     *
     * @throws ApiKeyNotFoundException
     */
    public function loadUserByApiKey($apiKey);
}
