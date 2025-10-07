<?php

namespace Kuzry\Przelewy24\Data;

use Kuzry\Przelewy24\Enums\Channel;
use Kuzry\Przelewy24\Enums\Country;
use Kuzry\Przelewy24\Enums\Currency;
use Kuzry\Przelewy24\Enums\Environment;
use Kuzry\Przelewy24\Enums\Language;
use Spatie\LaravelData\Data;

class Przelewy24ConfigData extends Data
{
    public function __construct(
        public readonly Environment $environment,
        public readonly bool $autoRegisterFrontendData,
        /** @var Przelewy24ConfigPosData[] */
        public readonly array $pos,
        public readonly Currency $currency,
        public readonly string $urlReturn,
        public readonly string $urlStatus,
        public readonly string $urlTransactionRegister,
        public readonly ?string $method,
        public readonly ?int $timeLimit,
        public readonly ?Channel $channel,
        public readonly ?bool $waitForResult,
        public readonly ?string $encoding,
        public readonly Country $country,
        public readonly Language $language,
        public readonly ?bool $regulationAccept,
    ) {}

    public static function fromConfig(): self
    {
        $posArray = [];

        $pos = config('przelewy24.pos');
        foreach ($pos as $posId => $posData) {
            $posArray[$posId] = new Przelewy24ConfigPosData(
                merchantId: $posData['merchant_id'],
                posId: $posData['pos_id'],
                reportKey: $posData['report_key'],
                crc: $posData['crc'],
            );
        }

        return new self(
            environment: config('przelewy24.environment'),
            autoRegisterFrontendData: config('przelewy24.auto_register_frontend_data'),
            pos: $posArray,
            currency: config('przelewy24.currency'),
            urlReturn: config('przelewy24.url_return'),
            urlStatus: config('przelewy24.url_status'),
            urlTransactionRegister: config('przelewy24.url_transaction_register'),
            method: config('przelewy24.method'),
            timeLimit: config('przelewy24.time_limit'),
            channel: config('przelewy24.channel'),
            waitForResult: config('przelewy24.wait_for_result'),
            encoding: config('przelewy24.encoding'),
            country: config('przelewy24.country'),
            language: config('przelewy24.language'),
            regulationAccept: config('przelewy24.regulation_accept'),
        );
    }
}
