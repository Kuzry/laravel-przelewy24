<?php

namespace Kuzry\Przelewy24\Api\Requests;

use Kuzry\Przelewy24\Api\Data\TransactionRegister\TransactionRegisterRequestData;
use Kuzry\Przelewy24\Api\Data\TransactionRegister\TransactionRegisterResponseData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class TransactionRegisterRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly TransactionRegisterRequestData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/transaction/register';
    }

    public function defaultBody(): array
    {
        return $this->data->toArray();
    }

    public function createDtoFromResponse(Response $response): TransactionRegisterResponseData
    {
        return TransactionRegisterResponseData::from(
            $response->json()['data']
        );
    }
}
