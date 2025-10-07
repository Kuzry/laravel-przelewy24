<?php

namespace Kuzry\Przelewy24\Api\Data\TransactionVerify;

use Kuzry\Przelewy24\Api\Data\AbstractSignRequestData;
use Kuzry\Przelewy24\Enums\Currency;
use Spatie\LaravelData\Attributes\Hidden;

class TransactionVerifyRequestData extends AbstractSignRequestData
{
    public function __construct(
        public readonly int $merchantId,
        public readonly int $posId,
        public readonly string $sessionId,
        public readonly int $amount,
        public readonly Currency $currency,
        public readonly int $orderId,
        #[Hidden]
        public readonly string $crc,
    ) {}

    protected function getSignData(): array
    {
        return [
            'sessionId' => $this->sessionId,
            'orderId' => $this->orderId,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'crc' => $this->crc,
        ];
    }
}
