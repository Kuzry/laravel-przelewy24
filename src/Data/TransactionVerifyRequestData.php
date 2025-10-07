<?php

namespace Kuzry\Przelewy24\Data;

use Illuminate\Http\Request;
use Kuzry\Przelewy24\Enums\Currency;
use Spatie\LaravelData\Data;

class TransactionVerifyRequestData extends Data
{
    public function __construct(
        public readonly string $sessionId,
        public readonly int $amount,
        public readonly int $orderId,
        public readonly ?Currency $currency = null,
        public readonly ?int $merchantId = null,
        public readonly ?int $posId = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            sessionId: $request->input('sessionId'),
            amount: (int) $request->input('amount'),
            orderId: (int) $request->input('orderId'),
            currency: Currency::from($request->input('currency')),
        );
    }
}
