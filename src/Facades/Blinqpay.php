<?php

namespace Geoslim\Blinqpay\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed performTransaction(array $transaction)
 *
 * @see \Geoslim\Blinqpay\Contracts\BlinqpayPaymentRoutingInterface
 */
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
