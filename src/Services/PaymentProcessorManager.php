<?php

namespace Geoslim\Blinqpay\Services;

use Geoslim\Blinqpay\Models\BlinqpayPaymentProcessor;

class PaymentProcessorManager
{
    private function processorQuery()
    {
        return BlinqpayPaymentProcessor::query();
    }

    public function getProcessors()
    {
        return $this->processorQuery()->get();
    }

    public function getActiveProcessors()
    {
        return $this->processorQuery()->where('status', 'active')->get();
    }

    public function getAProcessor($key, $value)
    {
        return $this->processorQuery()->where($key, $value)->first();
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
        return $this->processorQuery()->where('name', $name)->delete();
    }
}
