<?php

namespace Kuzry\Przelewy24\Api\Data\TransactionRegister;

use Spatie\LaravelData\Data;

class TransactionRegisterResponseData extends Data
{
    public function __construct(
        public readonly string $token,
    ) {}
}
