<?php

namespace Kuzry\Przelewy24\Api\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class TestAccessRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return 'testAccess';
    }
}
