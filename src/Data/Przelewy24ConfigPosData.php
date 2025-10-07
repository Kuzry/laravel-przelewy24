<?php

namespace Kuzry\Przelewy24\Data;

use Spatie\LaravelData\Data;

class Przelewy24ConfigPosData extends Data
{
    public function __construct(
        public readonly ?int $merchantId = null,
        public readonly ?int $posId = null,
        public readonly ?string $reportKey = null,
        public readonly ?string $crc = null,
    ) {}
}
