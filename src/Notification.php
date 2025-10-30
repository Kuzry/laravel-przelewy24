<?php

namespace Kuzry\Przelewy24;

use Kuzry\Przelewy24\Data\Przelewy24ConfigData;
use Kuzry\Przelewy24\Data\TransactionRegisterNotificationData;
use Kuzry\Przelewy24\Traits\PosTrait;

class Notification
{
    use PosTrait;

    public function __construct(
        protected Przelewy24ConfigData $config,
    ) {}

    public function isTransactionRegisterValid(string $sign, TransactionRegisterNotificationData $data): bool
    {
        $hash = hash(
            'sha384',
            (string) json_encode([
                'merchantId' => $data->merchantId ?? $this->config->pos[$this->pos]->merchantId,
                'posId' => $data->posId ?? $this->config->pos[$this->pos]->posId,
                'sessionId' => $data->sessionId,
                'amount' => $data->amount,
                'originAmount' => $data->originAmount,
                'currency' => $data->currency,
                'orderId' => $data->orderId,
                'methodId' => $data->methodId,
                'statement' => $data->statement,
                'crc' => $data->crc ?? $this->config->pos[$this->pos]->crc,
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        return hash_equals($sign, $hash);
    }
}
