<?php

namespace Geoslim\Blinqpay\Contracts;

interface BlinqpayPaymentProcessorInterface
{
    /**
     * @param array $transaction
     * @return mixed
     */
    public function processPayment(array $transaction);
}
