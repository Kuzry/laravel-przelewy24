<?php

namespace Kuzry\Przelewy24\Api\Data\TransactionRegister;

use Spatie\LaravelData\Data;

class TransactionRegisterCartRequestData extends Data
{
    public function __construct(
        public readonly string $sellerId,
        public readonly string $sellerCategory,
        public readonly ?string $name = null,
        public readonly ?string $description = null,
        public readonly ?int $quantity = null,
        public readonly ?int $price = null,
        public readonly ?string $number = null,
    ) {}
}
