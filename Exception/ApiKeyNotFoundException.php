<?php

/*
 * This file is part of the Helthe API Security package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Component\Security\Api\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * ApiKeyNotFoundException is thrown if a User cannot be found given the API key.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class ApiKeyNotFoundException extends AuthenticationException
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * {@inheritDoc}
     */
    public function getMessageKey()
    {
        return 'API key does not exist.';
    }

    /**
     * Get the API key.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set the API key.
     *
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->apiKey,
            parent::serialize(),
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($str)
    {
        list($this->apiKey, $parentData) = unserialize($str);

        parent::unserialize($parentData);
    }
}
