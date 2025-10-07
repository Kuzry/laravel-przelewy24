<?php

namespace Kuzry\Przelewy24\Api\Data\TransactionRegister;

use Spatie\LaravelData\Data;

class TransactionRegisterAdditionalShippingRequestData extends Data
{
    public function __construct(
        public readonly int $type,
        public readonly string $address,
    ) {}
}
