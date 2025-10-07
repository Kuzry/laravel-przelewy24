<?php

namespace Kuzry\Przelewy24\Api;

use Kuzry\Przelewy24\Api\Data\BasicAuthData;
use Kuzry\Przelewy24\Enums\Environment;
use Saloon\Http\Auth\BasicAuthenticator;
use Saloon\Http\Connector;

class Przelewy24Connector extends Connector
{
    private const string URL_PRODUCTION = 'https://secure.przelewy24.pl/api/v1';

    private const string URL_SANDBOX = 'https://sandbox.przelewy24.pl/api/v1';

    public function __construct(
        private readonly Environment $environment,
        private readonly BasicAuthData $basicAuthData,
    ) {}

    public function resolveBaseUrl(): string
    {
        return match ($this->environment->value) {
            Environment::PRODUCTION->value => self::URL_PRODUCTION,
            default => self::URL_SANDBOX,
        };
    }

    protected function defaultAuth(): BasicAuthenticator
    {
        return new BasicAuthenticator(
            username: $this->basicAuthData->username,
            password: $this->basicAuthData->password,
        );
    }

    public function getBaseUrl(): string
    {
        $parts = parse_url($this->resolveBaseUrl());

        return $parts['scheme'].'://'.$parts['host'];
    }
}
