<?php

namespace Geoslim\Blinqpay\Factories;

use Geoslim\Blinqpay\Contracts\BlinqpayPaymentProcessorInterface;
use Geoslim\Blinqpay\Exceptions\BlinqpayException;
use Illuminate\Support\Facades\App;

class PaymentProcessorAdapterFactory
{
    /**
     * Resolve the adapter instance for the given processor name.
     * @param string $processorName
     * @return BlinqpayPaymentProcessorInterface
     * @throws BlinqpayException
     */
    public function make(string $processorName): BlinqpayPaymentProcessorInterface
    {
        $adapter = App::make(config('blinqpay.processors.' . strtolower($processorName)));

        if ($adapter instanceof BlinqpayPaymentProcessorInterface) {
            return $adapter;
        }

        throw new BlinqpayException('Invalid adapter for processor: ' . $processorName);
    }
}
