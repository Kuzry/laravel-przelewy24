<?php

namespace Kuzry\Przelewy24\Data\FrontendData;

use Spatie\LaravelData\Data;

class FrontendTransactionData extends Data
{
    public function __construct(
        public readonly string $registerRoute,
    ) {}
}
