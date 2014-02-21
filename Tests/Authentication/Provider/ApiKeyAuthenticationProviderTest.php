<?php

/*
 * This file is part of the Helthe API Security package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Component\Security\Api\Tests\Authentication\Provider;

use Helthe\Component\Security\Api\Authentication\Provider\ApiKeyAuthenticationProvider;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

class ApiKeyAuthenticationProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testSupports()
    {
        $provider = $this->getProvider();

        $this->assertTrue($provider->supports($this->getPreAuthenticatedTokenMock()));
        $this->assertFalse($provider->supports($this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface')));

        $token = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken')
                    ->disableOriginalConstructor()
                    ->getMock()
        ;
        $token
            ->expects($this->once())
            ->method('getProviderKey')
            ->will($this->returnValue('foo'))
        ;
        $this->assertFalse($provider->supports($token));
    }

    public function testAuthenticateWhenTokenIsNotSupported()
    {
        $provider = $this->getProvider();

        $this->assertNull($provider->authenticate($this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface')));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\BadCredentialsException
     */
    public function testAuthenticateWhenNoUserIsSet()
    {
        $provider = $this->getProvider();
        $provider->authenticate($this->getPreAuthenticatedTokenMock(''));
    }

    public function testAuthenticate()
    {
        $user = $this->getMock('Symfony\Component\Security\Core\User\UserInterface');
        $user
            ->expects($this->once())
            ->method('getRoles')
            ->will($this->returnValue(array()))
        ;
        $provider = $this->getProvider($user);

        $token = $provider->authenticate($this->getPreAuthenticatedTokenMock('foo', 'bar'));
        $this->assertInstanceOf('Helthe\Component\Security\Api\Authentication\Token\ApiKeyAuthenticatedToken', $token);
        $this->assertEquals('bar', $token->getCredentials());
        $this->assertEquals('bar', $token->getApiKey());
        $this->assertEquals('key', $token->getProviderKey());
        $this->assertEquals(array(), $token->getRoles());
        $this->assertEquals(array('foo' => 'bar'), $token->getAttributes(), '->authenticate() copies token attributes');
        $this->assertSame($user, $token->getUser());
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\LockedException
     */
    public function testAuthenticateWhenUserCheckerThrowsException()
    {
        $user = $this->getMock('Symfony\Component\Security\Core\User\UserInterface');

        $userChecker = $this->getMock('Symfony\Component\Security\Core\User\UserCheckerInterface');
        $userChecker->expects($this->once())
                    ->method('checkPostAuth')
                    ->will($this->throwException($this->getMock('Symfony\Component\Security\Core\Exception\LockedException', null, array(), '', false)))
        ;

        $provider = $this->getProvider($user, $userChecker);

        $provider->authenticate($this->getPreAuthenticatedTokenMock('foo'));
    }

    /**
     * Get an instance of ApiKeyAuthenticationProvider.
     *
     * @param UserInterface        $user
     * @param UserCheckerInterface $userChecker
     *
     * @return ApiKeyAuthenticationProvider
     */
    private function getProvider($user = null, $userChecker = null)
    {
        $userProvider = $this->getMock('Helthe\Component\Security\Api\User\UserProviderInterface');
        if (null !== $user) {
            $userProvider->expects($this->once())
                         ->method('loadUserByApiKey')
                         ->will($this->returnValue($user))
            ;
        }

        if (null === $userChecker) {
            $userChecker = $this->getMock('Symfony\Component\Security\Core\User\UserCheckerInterface');
        }

        return new ApiKeyAuthenticationProvider($userProvider, $userChecker, 'key');
    }

    /**
     * Get a mock of PreAuthenticatedToken.
     *
     * @param string $user
     * @param string $credentials
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getPreAuthenticatedTokenMock($user = false, $credentials = false)
    {
        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken', array('getUser', 'getCredentials', 'getProviderKey'), array(), '', false);
        if (false !== $user) {
            $token->expects($this->once())
                  ->method('getUser')
                  ->will($this->returnValue($user))
            ;
        }
        if (false !== $credentials) {
            $token->expects($this->any())
                  ->method('getCredentials')
                  ->will($this->returnValue($credentials))
            ;
        }

        $token
            ->expects($this->any())
            ->method('getProviderKey')
            ->will($this->returnValue('key'))
        ;

        $token->setAttributes(array('foo' => 'bar'));

        return $token;
    }
}
