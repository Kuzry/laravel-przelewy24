<?php

namespace Kuzry\Przelewy24\Enums;

enum Environment: string
{
    case SANDBOX = 'sandbox';
    case PRODUCTION = 'production';
}
