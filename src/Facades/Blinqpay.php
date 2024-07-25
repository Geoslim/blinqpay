<?php

namespace Geoslim\Blinqpay\Facades;

use Illuminate\Support\Facades\Facade;

class Blinqpay extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'blinqpay';
    }
}