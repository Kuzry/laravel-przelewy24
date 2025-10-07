<?php

namespace Kuzry\Przelewy24\Api\Data\TransactionRegister;

use Kuzry\Przelewy24\Api\Data\AbstractSignRequestData;
use Kuzry\Przelewy24\Enums\Channel;
use Kuzry\Przelewy24\Enums\Country;
use Kuzry\Przelewy24\Enums\Currency;
use Kuzry\Przelewy24\Enums\Language;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Support\Transformation\TransformationContextFactory;

class TransactionRegisterRequestData extends AbstractSignRequestData
{
    public function __construct(
        public readonly int $merchantId,
        public readonly int $posId,
        public readonly string $sessionId,
        public readonly int $amount,
        public readonly Currency $currency,
        public readonly string $description,
        public readonly string $email,
        public readonly string $urlReturn,
        #[Hidden]
        public readonly string $crc,
        public readonly Country $country = Country::PL,
        public readonly Language $language = Language::PL,
        public readonly ?string $client = null,
        public readonly ?string $address = null,
        public readonly ?string $zip = null,
        public readonly ?string $city = null,
        public readonly ?string $phone = null,
        public readonly ?int $method = null,
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
        public readonly ?bool $regulationAccept = null,
    ) {}

    public function transform(
        null|TransformationContextFactory|TransformationContext $transformationContext = null
    ): array {
        return array_filter(
            parent::transform($transformationContext),
            fn ($value) => $value !== null,
        );
    }

    protected function getSignData(): array
    {
        return [
            'sessionId' => $this->sessionId,
            'merchantId' => $this->merchantId,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'crc' => $this->crc,
        ];
    }
}
