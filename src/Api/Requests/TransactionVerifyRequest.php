<?php

namespace Kuzry\Przelewy24\Api\Requests;

use Kuzry\Przelewy24\Api\Data\TransactionVerify\TransactionVerifyRequestData;
use Kuzry\Przelewy24\Api\Data\TransactionVerify\TransactionVerifyResponseData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class TransactionVerifyRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected readonly TransactionVerifyRequestData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/transaction/verify';
    }

    public function defaultBody(): array
    {
        return $this->data->toArray();
    }

    public function createDtoFromResponse(Response $response): TransactionVerifyResponseData
    {
        return TransactionVerifyResponseData::from(
            $response->json()['data']
        );
    }
}
