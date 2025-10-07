<?php

namespace Kuzry\Przelewy24;

use Illuminate\Support\Facades\Route;
use Kuzry\Przelewy24\Api\Przelewy24Connector;
use Kuzry\Przelewy24\Data\FrontendData\FrontendData;
use Kuzry\Przelewy24\Data\Przelewy24ConfigData;

final class Przelewy24
{
    public function __construct(
        private readonly Przelewy24ConfigData $config,
        private readonly Przelewy24Connector $connector,
    ) {}

    public function pos(string $posKey = 'default'): self
    {
        if (! isset($this->config->pos[$posKey])) {
            throw new \InvalidArgumentException("POS configuration '{$posKey}' not found");
        }

        return app(Przelewy24::class, ['pos_key' => $posKey]);
    }

    public function getFrontendData(): FrontendData
    {
        return FrontendData::from([
            'transaction' => [
                'registerRoute' => Route::has($this->config->urlTransactionRegister) ? route($this->config->urlTransactionRegister) : $this->config->urlTransactionRegister,
            ],
        ]);
    }

    public function notification(): Notification
    {
        return new Notification($this->config);
    }

    public function transaction(): Transaction
    {
        return new Transaction($this->config, $this->connector);
    }
}
