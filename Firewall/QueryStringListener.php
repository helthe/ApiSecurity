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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * Authentication listener that checks for the API key in the query string.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class QueryStringListener extends AbstractListener
{
    /**
     * {@inheritdoc}
     */
    protected function getPreAuthenticatedData(Request $request)
    {
        if (!$request->query->has($this->fieldName)) {
            throw new BadCredentialsException(sprintf('No API key found in the query string. Please use the "%s" query string.', $this->fieldName));
        }

        if (!$request->query->get($this->fieldName)) {
            throw new BadCredentialsException('No API key found in the query string.');
        }

        return array('anon.', $request->query->get($this->fieldName));
    }
}
