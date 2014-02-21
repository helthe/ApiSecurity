# Helthe API Security [![Build Status](https://travis-ci.org/helthe/ApiSecurity.png?branch=master)](https://travis-ci.org/helthe/ApiSecurity) [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/helthe/ApiSecurity/badges/quality-score.png?s=aebda5f26e67d84fa00a4707b635a69e3e408ac5)](https://scrutinizer-ci.com/g/helthe/ApiSecurity/)

Helthe API Security is a library for doing API key authentication with the
Symfony [Security Component](http://symfony.com/doc/current/components/security/introduction.html).

## Installation

### Using Composer

#### Manually

Add the following in your `composer.json`:

```json
{
    "require": {
        // ...
        "helthe/security-api": "~1.0"
    }
}
```

#### Using the command line

```bash
$ composer require 'helthe/security-api=~1.0'
```

## Usage

### Authentication Provider

An API authentication provider implementing `AuthenticationProviderInterface`is
supplied supporting the `PreAuthenticatedToken`. Once authenticated, a user will be
authenticated using a `ApiKeyAuthenticatedToken` which is an extension of `PreAuthenticatedToken`
where the api key is not erased.

#### User Provider

The library provides its own `UserProviderInterface` that must implemented by the
user provider supplied to the `ApiKeyAuthenticationProvider`.

#### Example

```php
use Helthe\Component\Security\Api\Authentication\Provider\ApiKeyAuthenticationProvider;
use Symfony\Component\Security\Core\User\UserChecker;

// Helthe\Component\Security\Api\User\UserProviderInterface
$userProvider = new InMemoryUserProvider(
    array(
        'admin' => array(
            'api_key' => 'foo',
        ),
    )
);

// for some extra checks: is account enabled, locked, expired, etc.?
$userChecker = new UserChecker();

$provider = new ApiKeyAuthenticationProvider(
    $userProvider,
    $userChecker,
    'your_api',
);

$provider->authenticate($unauthenticatedToken);
```

### Firewall

Two firewall listeners are available extending `AbstractPreAuthenticatedListener`.
`HttpHeaderListener` checks for the api key in the `Request` headers and `QueryStringListener`
checks in the `Request` query string.

## Bugs

For bugs or feature requests, please [create an issue](https://github.com/helthe/ApiSecurity/issues/new).
