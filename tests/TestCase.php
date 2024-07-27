<?php

namespace Geoslim\Blinqpay\Tests;

use Geoslim\Blinqpay\Models\BlinqpayPaymentProcessor;
use Geoslim\Blinqpay\Providers\BlinqpayServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

class TestCase extends  \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;
    /**
     * Automatically enables package discoveries.
     *
     * @var bool
     */
    protected $enablesPackageDiscoveries = true;

    /**
     * Setup the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__ . '/../src/database/migrations');
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
        // Seed the database with default data
        $this->seedDatabase();
    }

    /**
     * Get package providers.
     *
     * @param  Application  $app
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders($app): array
    {
        return [
            BlinqpayServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('blinqpay', require __DIR__.'/../config/blinqpay.php');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

    }

    /**
     * Override application aliases.
     *
     * @param  Application  $app
     * @return array<string, class-string<Facade>>
     */
    protected function getPackageAliases($app): array
    {
        return [
            'Blinqpay' => 'Geoslim\Blinqpay\Facades\Blinqpay',
        ];
    }

    private function seedDatabase()
    {

        BlinqpayPaymentProcessor::updateOrCreate(
            ['name' => 'Fincra'],
            [
                'reliability' => 0.8,
                'transaction_fee' => 100,
                'supported_currencies' => [
                    'NGN', 'USD', 'EUR'
                ]
            ]
        );

        BlinqpayPaymentProcessor::updateOrCreate(
            ['name' => 'Payoneer'],
            [
                'reliability' => 0.6,
                'transaction_fee' => 100,
                'supported_currencies' => [
                    'NGN', 'USD', 'EUR'
                ]
            ]
        );

        BlinqpayPaymentProcessor::updateOrCreate(
            ['name' => 'Paystack'],
            [
                'reliability' => 0.5,
                'transaction_fee' => 150,
                'supported_currencies' => [
                    'NGN'
                ]
            ]
        );

        BlinqpayPaymentProcessor::updateOrCreate(
            ['name' => 'RoyalBank'],
            [
                'reliability' => 0.5,
                'transaction_fee' => 150,
                'supported_currencies' => [
                    'GBP'
                ]
            ]
        );
    }
}