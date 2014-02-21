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
use Helthe\Component\Security\Api\Firewall\HttpHeaderListener;

class HttpHeaderListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetPreAuthenticatedData()
    {
        $request = new Request(array(), array(), array(), array(), array(), array('HTTP_API_KEY' => 'foo'));

        $securityContext = $this->getMock('Symfony\Component\Security\Core\SecurityContextInterface');

        $authenticationManager = $this->getMock('Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface');

        $listener = new HttpHeaderListener($securityContext, $authenticationManager, 'TheProviderKey', 'api-key');

        $method = new \ReflectionMethod($listener, 'getPreAuthenticatedData');
        $method->setAccessible(true);

        $result = $method->invokeArgs($listener, array($request));
        $this->assertSame($result, array('anon.', 'foo'));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\BadCredentialsException
     * @expectedExceptionMessage No API key found in the header. Please use the "api-key" header.
     */
    public function testGetPreAuthenticatedDataWithWrongHeader()
    {
        $request = new Request(array(), array(), array(), array(), array(), array('HTTP_FOO_KEY' => 'foo'));

        $securityContext = $this->getMock('Symfony\Component\Security\Core\SecurityContextInterface');

        $authenticationManager = $this->getMock('Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface');

        $listener = new HttpHeaderListener($securityContext, $authenticationManager, 'TheProviderKey', 'api-key');

        $method = new \ReflectionMethod($listener, 'getPreAuthenticatedData');
        $method->setAccessible(true);

        $result = $method->invokeArgs($listener, array($request));
        $this->assertSame($result, array('anon.', 'foo'));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\BadCredentialsException
     * @expectedExceptionMessage No API key found in the header.
     */
    public function testGetPreAuthenticatedDataWithEmptyHeader()
    {
        $request = new Request(array(), array(), array(), array(), array(), array('HTTP_API_KEY' => ''));

        $securityContext = $this->getMock('Symfony\Component\Security\Core\SecurityContextInterface');

        $authenticationManager = $this->getMock('Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface');

        $listener = new HttpHeaderListener($securityContext, $authenticationManager, 'TheProviderKey', 'api-key');

        $method = new \ReflectionMethod($listener, 'getPreAuthenticatedData');
        $method->setAccessible(true);

        $result = $method->invokeArgs($listener, array($request));
        $this->assertSame($result, array('anon.', 'foo'));
    }
}
