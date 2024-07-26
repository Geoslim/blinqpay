<?php

namespace Geoslim\Blinqpay\Providers;

use Geoslim\Blinqpay\Contracts\BlinqpayPaymentProcessorInterface;
use Geoslim\Blinqpay\Exceptions\BlinqpayException;
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
        $this->bindPaymentProcessorAdapters();
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
            ], 'blinqpay');
        }

        return $this;
    }

    protected function registerMigrations(): BlinqpayServiceProvider
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ]);
        }
        return $this;
    }

    protected function bindPaymentProcessorAdapters()
    {
        $adapters = config('blinqpay.adapters');

        foreach ($adapters as $name => $class) {
            try {
                $this->app->bind('blinqpay.' . $name, $class);
            } catch (BlinqpayException $e) {
                Log::error("Failed to bind adapter {$name}: " . $e->getMessage());
            }
        }
    }
}
