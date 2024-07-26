<?php

namespace Geoslim\Blinqpay\Contracts;

interface BlinqpayPaymentRoutingInterface
{
    /**
     * @param array $transaction
     * @return mixed
     */
    public function performTransaction(array $transaction);
}