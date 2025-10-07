<?php

namespace Kuzry\Przelewy24\Enums;

enum Channel: int
{
    case CARD_APPLE_PAY_GOOGLE_PAY = 1;

    case TRANSFERS = 2;

    case TRADITIONAL_TRANSFERS = 4;
}
