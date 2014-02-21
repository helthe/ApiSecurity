<?php

/*
 * This file is part of the Helthe API Security package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Component\Security\Api\Tests\Authentication\Token;

use Helthe\Component\Security\Api\Authentication\Token\ApiKeyAuthenticatedToken;
use Symfony\Component\Security\Core\Role\Role;

class ApiKeyAuthenticatedTokenTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $token = new ApiKeyAuthenticatedToken('foo', 'bar', 'key');
        $this->assertFalse($token->isAuthenticated());

        $token = new ApiKeyAuthenticatedToken('foo', 'bar', 'key', array('ROLE_FOO'));
        $this->assertTrue($token->isAuthenticated());
        $this->assertEquals(array(new Role('ROLE_FOO')), $token->getRoles());
        $this->assertEquals('key', $token->getProviderKey());
    }

    public function testGetApiKey()
    {
        $token = new ApiKeyAuthenticatedToken('foo', 'bar', 'key');
        $this->assertEquals('bar', $token->getApiKey());
    }

    public function testEraseCredentials()
    {
        $token = new ApiKeyAuthenticatedToken('foo', 'bar', 'key');
        $token->eraseCredentials();
        $this->assertEquals('', $token->getCredentials());
        $this->assertEquals('bar', $token->getApiKey());
    }
}
