<?php

/*
 * This file is part of the Helthe API Security package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Component\Security\Api\Firewall;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Firewall\AbstractPreAuthenticatedListener;

/**
 * Base authentication listener for using API key authentication.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
abstract class AbstractListener extends AbstractPreAuthenticatedListener
{
    /**
     * @var string
     */
    protected $fieldName;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface       $securityContext
     * @param AuthenticationManagerInterface $authenticationManager
     * @param string                         $providerKey
     * @param string                         $fieldName
     * @param LoggerInterface                $logger
     * @param EventDispatcherInterface       $dispatcher
     */
    public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager, $providerKey, $fieldName, LoggerInterface $logger = null, EventDispatcherInterface $dispatcher = null)
    {
        parent::__construct($securityContext, $authenticationManager, $providerKey, $logger, $dispatcher);

        $this->fieldName = $fieldName;
    }
}
