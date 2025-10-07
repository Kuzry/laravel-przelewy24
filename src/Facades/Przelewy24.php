<?php

namespace Kuzry\Przelewy24\Facades;

use Illuminate\Support\Facades\Facade;

class Przelewy24 extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Kuzry\Przelewy24\Przelewy24::class;
    }
}
