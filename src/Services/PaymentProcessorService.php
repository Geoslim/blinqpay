<?php

namespace Geoslim\Blinqpay\Services;

use Geoslim\Blinqpay\Exceptions\BlinqpayException;
use Geoslim\Blinqpay\Models\BlinqpayPaymentProcessor;

class PaymentProcessorService
{
    protected PaymentProcessorManager $paymentProcessorManager;

    public function __construct(PaymentProcessorManager $paymentProcessorManager)
    {
        $this->paymentProcessorManager = $paymentProcessorManager;
    }

    /**
     * @param array $transaction
     * @return mixed|null
     * @throws BlinqpayException
     */
    public function getPaymentProcessor(array $transaction)
    {
        $processors = $this->paymentProcessorManager->getActiveProcessors();
        $supportedProcessors = $this->checkCurrencySupport($processors, $transaction['currency']);

        return $this->decideBestPaymentProcessor($supportedProcessors);
    }

    /**
     * @param $processors
     * @param string $currency
     * @return array
     * @throws BlinqpayException
     */
    public function checkCurrencySupport($processors, string $currency): array
    {
        $supportedProcessors = [];

        foreach($processors as $processor) {
            if ($this->supportsCurrency($processor, $currency)) {
                $supportedProcessors[] = $processor;
            }
        }

        if (empty($supportedProcessors)) {
            throw new BlinqpayException('No suitable payment processor found for currency: ' . $currency);
        }

        return $supportedProcessors;
    }

    /**
     * @param BlinqpayPaymentProcessor $processor
     * @param string $currency
     * @return bool
     */
    public function supportsCurrency(BlinqpayPaymentProcessor $processor, string $currency): bool
    {
        $supportedCurrencies = is_array($processor->supported_currencies)
            ? $processor->supported_currencies
            : explode(',', $processor->supported_currencies);

        return in_array($currency, $supportedCurrencies);
    }

    protected function decideBestPaymentProcessor($supportedProcessors)
    {
        $bestProcessor = null;
        $bestScore = -INF;

        foreach ($supportedProcessors as $processor) {
            $score = $this->calculateScore($processor);
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestProcessor = $processor;
            }
        }

        return $bestProcessor;
    }

    /**
     * @param BlinqpayPaymentProcessor $processor
     * @return float|int
     */
    private function calculateScore(BlinqpayPaymentProcessor $processor)
    {
        $rules = config('blinqpay.routing_rules');
        $score = 0;

        // Cost factor evaluation
        if (isset($rules['transaction_fee'])) {
            $score += $rules['transaction_fee'] * (1 / $processor->transaction_fee);
        }

        // Reliability factor evaluation
        if (isset($rules['reliability'])) {
            $score += $rules['reliability'] * $processor->reliability;
        }

        return $score;
    }
}
