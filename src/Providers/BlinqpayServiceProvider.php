<?php

namespace Geoslim\Blinqpay\Providers;

use Geoslim\Blinqpay\Contracts\BlinqpayPaymentProcessorInterface;
use Geoslim\Blinqpay\Exceptions\BlinqpayException;
use Geoslim\Blinqpay\Factories\PaymentProcessorAdapterFactory;
use Geoslim\Blinqpay\Services\PaymentProcessorManager;
use Geoslim\Blinqpay\Services\PaymentProcessorRouter;
use Geoslim\Blinqpay\Services\PaymentProcessorService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class BlinqpayServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge configuration file
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/blinqpay.php', 'blinqpay'
        );
        $this->bindPaymentProcessorClasses();
    }

    public function boot(): void
    {
        $this->registerConfig()
            ->registerMigrations();
    }

    protected function registerConfig(): BlinqpayServiceProvider
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/blinqpay.php' => config_path('blinqpay.php'),
            ], 'blinqpay-config');
        }

        return $this;
    }

    protected function registerMigrations(): BlinqpayServiceProvider
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'blinqpay-migrations');

        }
        return $this;
    }

    protected function bindPaymentProcessorClasses()
    {
        // Bind the PaymentProcessorRouter to the 'blinqpay' key
        $this->app->singleton('blinqpay', function ($app) {
            return new PaymentProcessorRouter(
                $app->make(PaymentProcessorService::class),
                $app->make(PaymentProcessorAdapterFactory::class),
            );
        });

        // Binding AdapterFactory
        $this->app->singleton(PaymentProcessorAdapterFactory::class, function () {
            return new PaymentProcessorAdapterFactory();
        });

        // Binding PaymentProcessorService
        $this->app->singleton(PaymentProcessorService::class, function ($app) {
            return new PaymentProcessorService(
                $app->make(PaymentProcessorManager::class)
            );
        });

        // Binding PaymentProcessorRouter with dependencies injected
        $this->app->singleton(PaymentProcessorRouter::class, function ($app) {
            return new PaymentProcessorRouter(
                $app->make(PaymentProcessorService::class),
                $app->make(PaymentProcessorAdapterFactory::class),
            );
        });
    }
}
