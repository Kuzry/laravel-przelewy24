<?php

namespace Kuzry\Przelewy24\Data\FrontendData;

use Spatie\LaravelData\Data;

class FrontendData extends Data
{
    public function __construct(
        public readonly FrontendTransactionData $transaction,
    ) {}
}
