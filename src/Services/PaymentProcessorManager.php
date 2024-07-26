<?php

namespace Geoslim\Blinqpay\Services;

use Geoslim\Blinqpay\Models\BlinqpayPaymentProcessor;

class PaymentProcessorManager
{
    public function getProcessors()
    {
        return BlinqpayPaymentProcessor::get();
    }

    public function getActiveProcessors()
    {
        return BlinqpayPaymentProcessor::where('active', true)->get();
    }

    public function addProcessor(string $name, array $configuration)
    {
        return BlinqpayPaymentProcessor::updateOrCreate(
            ['name' => $name],
            $configuration
        );
    }

    public function updateProcessor(string $name, array $configuration)
    {
        return $this->addProcessor($name, $configuration);
    }

    public function removeProcessor(string $name)
    {
        return BlinqpayPaymentProcessor::where('name', $name)->delete();
    }
}
