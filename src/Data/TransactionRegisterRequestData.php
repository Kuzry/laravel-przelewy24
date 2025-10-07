<?php

namespace Kuzry\Przelewy24\Data;

use Kuzry\Przelewy24\Api\Data\TransactionRegister\TransactionRegisterAdditionalRequestData;
use Kuzry\Przelewy24\Api\Data\TransactionRegister\TransactionRegisterCartRequestData;
use Kuzry\Przelewy24\Enums\Channel;
use Kuzry\Przelewy24\Enums\Country;
use Kuzry\Przelewy24\Enums\Currency;
use Kuzry\Przelewy24\Enums\Language;
use Spatie\LaravelData\Data;

class TransactionRegisterRequestData extends Data
{
    public function __construct(
        public readonly string $sessionId,
        public readonly int $amount,
        public readonly string $description,
        public readonly string $email,
        public readonly ?int $merchantId = null,
        public readonly ?int $posId = null,
        public readonly ?Currency $currency = null,
        public readonly ?string $client = null,
        public readonly ?string $address = null,
        public readonly ?string $zip = null,
        public readonly ?string $city = null,
        public readonly ?string $phone = null,
        public readonly ?int $method = null,
        public readonly ?string $urlReturn = null,
        public readonly ?string $urlStatus = null,
        public readonly ?int $timeLimit = null,
        public readonly ?Channel $channel = null,
        public readonly ?bool $waitForResult = null,
        public readonly ?int $shipping = null,
        public readonly ?string $transferLabel = null,
        public readonly ?string $encoding = null,
        public readonly ?string $methodRefId = null,
        public readonly ?TransactionRegisterCartRequestData $cart = null,
        public readonly ?TransactionRegisterAdditionalRequestData $additional = null,
        public readonly ?string $crc = null,
        public readonly ?Country $country = Country::PL,
        public readonly ?Language $language = Language::PL,
        public readonly ?bool $regulationAccept = null,
    ) {}
}
