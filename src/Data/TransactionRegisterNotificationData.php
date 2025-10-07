<?php

namespace Kuzry\Przelewy24\Data;

use Kuzry\Przelewy24\Enums\Currency;
use Spatie\LaravelData\Data;

class TransactionRegisterNotificationData extends Data
{
    public function __construct(
        public readonly string $sessionId,
        public readonly int $amount,
        public readonly int $originAmount,
        public readonly Currency $currency,
        public readonly int $orderId,
        public readonly int $methodId,
        public readonly string $statement,
        public readonly ?int $merchantId = null,
        public readonly ?int $posId = null,
        public readonly ?string $crc = null,
    ) {}
}
