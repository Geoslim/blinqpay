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
        $adapterClass = config('blinqpay.processors.' . strtolower($processorName));

        if (is_null($adapterClass)) {
            throw new BlinqpayException('Invalid adapter for processor: ' . $processorName);
        }
        $adapter = App::make(config('blinqpay.processors.' . strtolower($processorName)));

        if (!$adapter instanceof BlinqpayPaymentProcessorInterface) {
            throw new BlinqpayException('Invalid adapter for processor: ' . $processorName);
        }

        return $adapter;
    }
}
