<?php

namespace Kuzry\Przelewy24\Api\Data\TransactionVerify;

use Spatie\LaravelData\Data;

class TransactionVerifyResponseData extends Data
{
    public function __construct(
        public readonly string $status,
    ) {}
}
