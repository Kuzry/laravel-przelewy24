<?php

namespace Kuzry\Przelewy24\Api\Data;

use Spatie\LaravelData\Data;

class BasicAuthData extends Data
{
    public function __construct(
        public readonly string $username,
        public readonly string $password,
    ) {}
}
