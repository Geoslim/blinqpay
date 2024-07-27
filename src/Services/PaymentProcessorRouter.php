<?php

namespace Geoslim\Blinqpay\Services;

use Geoslim\Blinqpay\Contracts\BlinqpayPaymentRoutingInterface;
use Geoslim\Blinqpay\Exceptions\BlinqpayException;
use Geoslim\Blinqpay\Factories\PaymentProcessorAdapterFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use Exception;

class PaymentProcessorRouter implements BlinqpayPaymentRoutingInterface
{
    protected PaymentProcessorService $paymentProcessorService;
    protected PaymentProcessorAdapterFactory $paymentProcessorAdapterFactory;

    public function __construct(
        PaymentProcessorService $paymentProcessorService,
        PaymentProcessorAdapterFactory $paymentProcessorAdapterFactory
    )
    {
        $this->paymentProcessorService = $paymentProcessorService;
        $this->paymentProcessorAdapterFactory = $paymentProcessorAdapterFactory;
    }

    /**
     * @param array $transaction
     * @return mixed
     * @throws BlinqpayException
     */
    public function performTransaction(array $transaction)
    {
        try {
            $processor = $this->paymentProcessorService->getPaymentProcessor($transaction);

            if ($processor) {
                $adapter = $this->paymentProcessorAdapterFactory->make($processor->name);

                $result = $adapter->processPayment($transaction);
                Log::info('Payment processed successfully', [
                    'processor' => $processor->name,
                    'transaction' => $transaction,
                    'result' => $result,
                ]);
                return $result;
            } else {
                throw new BlinqpayException('No suitable payment processor found');
            }
        } catch (Exception $e) {
            Log::error("Payment processing failed: " . $e->getMessage(), [
                'transaction' => $transaction,
                'exception' => $e
            ]);
            throw $e;
        }
    }
}
