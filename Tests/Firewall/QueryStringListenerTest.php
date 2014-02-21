<?php

/*
 * This file is part of the Helthe API Security package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Component\Security\Api\Tests\Firewall;

use Symfony\Component\HttpFoundation\Request;
use Helthe\Component\Security\Api\Firewall\QueryStringListener;

class QueryStringListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetPreAuthenticatedData()
    {
        $request = new Request(array('api_key' => 'foo'));

        $securityContext = $this->getMock('Symfony\Component\Security\Core\SecurityContextInterface');

        $authenticationManager = $this->getMock('Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface');

        $listener = new QueryStringListener($securityContext, $authenticationManager, 'TheProviderKey', 'api_key');

        $method = new \ReflectionMethod($listener, 'getPreAuthenticatedData');
        $method->setAccessible(true);

        $result = $method->invokeArgs($listener, array($request));
        $this->assertSame($result, array('anon.', 'foo'));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\BadCredentialsException
     * @expectedExceptionMessage No API key found in the query string. Please use the "api_key" query string.
     */
    public function testGetPreAuthenticatedDataWithWrongHeader()
    {
        $request = new Request(array('foo_key' => 'foo'));

        $securityContext = $this->getMock('Symfony\Component\Security\Core\SecurityContextInterface');

        $authenticationManager = $this->getMock('Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface');

        $listener = new QueryStringListener($securityContext, $authenticationManager, 'TheProviderKey', 'api_key');

        $method = new \ReflectionMethod($listener, 'getPreAuthenticatedData');
        $method->setAccessible(true);

        $result = $method->invokeArgs($listener, array($request));
        $this->assertSame($result, array('anon.', 'foo'));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\BadCredentialsException
     * @expectedExceptionMessage No API key found in the query string.
     */
    public function testGetPreAuthenticatedDataWithEmptyHeader()
    {
        $request = new Request(array('api_key' => ''));

        $securityContext = $this->getMock('Symfony\Component\Security\Core\SecurityContextInterface');

        $authenticationManager = $this->getMock('Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface');

        $listener = new QueryStringListener($securityContext, $authenticationManager, 'TheProviderKey', 'api_key');

        $method = new \ReflectionMethod($listener, 'getPreAuthenticatedData');
        $method->setAccessible(true);

        $result = $method->invokeArgs($listener, array($request));
        $this->assertSame($result, array('anon.', 'foo'));
    }
}