<?php

namespace Kuzry\Przelewy24;

use Illuminate\Support\Facades\Route;
use Kuzry\Przelewy24\Api\Data\TransactionRegister\TransactionRegisterRequestData as Przelewy24TransactionRegisterRequestData;
use Kuzry\Przelewy24\Api\Data\TransactionVerify\TransactionVerifyRequestData as Przelewy24TransactionVerifyRequestData;
use Kuzry\Przelewy24\Api\Przelewy24Connector;
use Kuzry\Przelewy24\Api\Requests\TransactionRegisterRequest;
use Kuzry\Przelewy24\Api\Requests\TransactionVerifyRequest;
use Kuzry\Przelewy24\Data\Przelewy24ConfigData;
use Kuzry\Przelewy24\Data\TransactionRegisterRequestData;
use Kuzry\Przelewy24\Data\TransactionRegisterResponseData;
use Kuzry\Przelewy24\Data\TransactionVerifyRequestData;
use Kuzry\Przelewy24\Data\TransactionVerifyResponseData;
use Kuzry\Przelewy24\Traits\PosTrait;

class Transaction
{
    use PosTrait;

    public function __construct(
        protected readonly Przelewy24ConfigData $config,
        protected readonly Przelewy24Connector $connector,
    ) {}

    public function register(TransactionRegisterRequestData $data): TransactionRegisterResponseData
    {
        $przelewy24TransactionRegisterRequest = new TransactionRegisterRequest(
            Przelewy24TransactionRegisterRequestData::from([
                'merchantId' => $data->merchantId ?? $this->config->pos[$this->pos]->merchantId,
                'posId' => $data->posId ?? $this->config->pos[$this->pos]->posId,
                'sessionId' => $data->sessionId,
                'amount' => $data->amount,
                'currency' => $data->currency ?? $this->config->currency,
                'description' => $data->description,
                'email' => $data->email,
                'client' => $data->client,
                'address' => $data->address,
                'zip' => $data->zip,
                'city' => $data->city,
                'phone' => $data->phone,
                'method' => $data->method,
                'urlReturn' => $data->urlReturn ?? Route::has($this->config->urlReturn) ? route($this->config->urlReturn) : $this->config->urlReturn,
                'urlStatus' => $data->urlStatus ?? Route::has($this->config->urlStatus) ? route($this->config->urlStatus) : $this->config->urlStatus,
                'timeLimit' => $data->timeLimit ?? $this->config->timeLimit,
                'channel' => $data->channel ?? $this->config->channel,
                'waitForResult' => $data->waitForResult ?? $this->config->waitForResult,
                'shipping' => $data->shipping,
                'transferLabel' => $data->transferLabel,
                'encoding' => $data->encoding ?? $this->config->encoding,
                'methodRefId' => $data->methodRefId,
                'cart' => $data->cart,
                'additional' => $data->additional,
                'crc' => $data->crc ?? $this->config->pos[$this->pos]->crc,
                'country' => $data->country ?? $this->config->country,
                'language' => $data->language ?? $this->config->language,
                'regulationAccept' => $data->regulationAccept ?? $this->config->regulationAccept,
            ]),
        );

        $transactionRegisterResponseData = $this->connector->send($przelewy24TransactionRegisterRequest)
            ->dtoOrFail();

        return TransactionRegisterResponseData::from([
            'redirectUrl' => "{$this->connector->getBaseUrl()}/trnRequest/$transactionRegisterResponseData->token",
        ]);
    }

    public function verify(TransactionVerifyRequestData $data): TransactionVerifyResponseData
    {
        $przelewy24TransactionVerifyRequest = new TransactionVerifyRequest(
            Przelewy24TransactionVerifyRequestData::from([
                'merchantId' => $data->merchantId ?? $this->config->pos[$this->pos]->merchantId,
                'posId' => $data->posId ?? $this->config->pos[$this->pos]->posId,
                'sessionId' => $data->sessionId,
                'amount' => $data->amount,
                'currency' => $data->currency ?? $this->config->currency,
                'orderId' => $data->orderId,
                'crc' => $data->crc ?? $this->config->pos[$this->pos]->crc,
            ])
        );

        $transactionVerifyResponseData = $this->connector->send($przelewy24TransactionVerifyRequest)
            ->dtoOrFail();

        return TransactionVerifyResponseData::from([
            'isSuccess' => $transactionVerifyResponseData->status === 'success',
        ]);
    }
}
