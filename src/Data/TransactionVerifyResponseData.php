<?php

namespace Kuzry\Przelewy24\Data;

use Spatie\LaravelData\Data;

class TransactionVerifyResponseData extends Data
{
    public function __construct(
        public readonly bool $isSuccess,
    ) {}
}
