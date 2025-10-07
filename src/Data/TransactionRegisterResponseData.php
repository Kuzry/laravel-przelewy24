<?php

namespace Kuzry\Przelewy24\Data;

use Spatie\LaravelData\Data;

class TransactionRegisterResponseData extends Data
{
    public function __construct(
        public readonly string $redirectUrl,
    ) {}
}
