<?php

namespace Kuzry\Przelewy24\Api\Data\TransactionRegister;

use Spatie\LaravelData\Data;

class TransactionRegisterAdditionalRequestData extends Data
{
    public function __construct(
        public readonly TransactionRegisterAdditionalShippingRequestData $shipping,
        public readonly TransactionRegisterAdditionalPSURequestData $PSU,
    ) {}
}
