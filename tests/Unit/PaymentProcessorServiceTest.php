<?php

namespace Geoslim\Blinqpay\Tests\Unit;

use Geoslim\Blinqpay\Exceptions\BlinqpayException;
use Geoslim\Blinqpay\Models\BlinqpayPaymentProcessor;
use Geoslim\Blinqpay\Services\PaymentProcessorManager;
use Geoslim\Blinqpay\Services\PaymentProcessorService;
use Geoslim\Blinqpay\Tests\TestCase;
use Illuminate\Contracts\Container\BindingResolutionException;
use Mockery;

class PaymentProcessorServiceTest extends TestCase
{
    protected $paymentProcessorManager;
    protected $paymentProcessorService;

    /**
     * @throws BindingResolutionException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->paymentProcessorManager = $this->app->make(PaymentProcessorManager::class);
        $this->paymentProcessorService = $this->app->make(PaymentProcessorService::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testSupportsCurrency()
    {
        $processor = new BlinqpayPaymentProcessor([
            'supported_currencies' => 'NGN, USD, EUR'
        ]);

        $service = $this->paymentProcessorService;
        $this->assertTrue($service->supportsCurrency($processor, 'NGN'));
        $this->assertFalse($service->supportsCurrency($processor, 'GBP'));
    }

    public function testGetPaymentProcessorWithValidProcessor()
    {
        $transaction = ['currency' => 'NGN', 'amount' => 1000];

        $processor = $this->paymentProcessorService->getPaymentProcessor($transaction);

        $this->assertInstanceOf(BlinqpayPaymentProcessor::class, $processor);
        $this->assertContains('NGN', $processor->supported_currencies);
    }

    public function testGetPaymentProcessorWithInvalidCurrency()
    {
        $this->expectException(BlinqpayException::class);

        $transaction = ['currency' => 'JPY', 'amount' => 1000];
        $this->paymentProcessorService->getPaymentProcessor($transaction);
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testDecideBestPaymentProcessor()
    {
        $processors = $this->paymentProcessorManager->getProcessors();

        $bestProcessor = $this->invokeMethod($this->paymentProcessorService, 'decideBestPaymentProcessor', [$processors]);

        $this->assertInstanceOf(BlinqpayPaymentProcessor::class, $bestProcessor);
        $this->assertEquals('Fincra', $bestProcessor->name);
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testCalculateScore()
    {
        $processor = $this->paymentProcessorManager->getAProcessor('name', 'Payoneer');

        $score = $this->invokeMethod($this->paymentProcessorService, 'calculateScore', [$processor]);

        $this->assertIsNumeric($score);
        $this->assertGreaterThan(0, $score);
    }

    /**
     * @param $object
     * @param $methodName
     * @param array $parameters
     * @return mixed
     * @throws \ReflectionException
     */
    protected function invokeMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}