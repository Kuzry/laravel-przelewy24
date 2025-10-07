<?php

namespace Kuzry\Przelewy24\Api\Data\TransactionRegister;

use Spatie\LaravelData\Data;

class TransactionRegisterAdditionalPSURequestData extends Data
{
    public function __construct(
        public readonly string $IP,
        public readonly string $userAgent,
    ) {}
}
