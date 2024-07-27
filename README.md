# Blinqpay Payment Processor Package

## Introduction

Blinqpay is a payment processing package for Laravel applications. It provides a flexible and extensible way to route payment transactions to the most suitable payment processor based on various criteria such as transaction cost, supported currency and reliability.

## Documentation, Installation, and Usage Instructions

## Version Compatibility

 Laravel  | Blinqpay
:---------|:------------------
 8.x, 9.x, 10.x, 11.x     | 1.x


## Installation

Add this to your composer. json file just before the closing curly brace
```php
  "repositories": [
        {
            "type": "github",
            "url": "https://github.com/Geoslim/blinqpay"
        }
    ]
```
Require this package with composer using the following command:
```php
composer require geoslim/blinqpay dev-main
```

## Configuration & Usage
After installation, you can publish the configuration and migration file using the following commands:
```php
php artisan vendor:publish --tag=blinqpay-config
php artisan vendor:publish --tag=blinqpay-migrations
```
- Run Migration

- Seed your database with your preferred payment processors

- Create your Payment Processor Classes and implement the `BlinqpayPaymentProcessorInterface` interface

```php
<?php

namespace App\Services;

use Geoslim\Blinqpay\Contracts\BlinqpayPaymentProcessorInterface;

class FincraAdapter implements BlinqpayPaymentProcessorInterface
{
    public function processPayment(array $transaction)
    {
        // TODO: Implement processPayment() method.
    }
}

```
- Set up your payment processors in the config/blinqpay.php configuration file. The package resolves the packages listed below

```php
 /*
   |--------------------------------------------------------------------------
   | Package Payment Processor aliases
   |--------------------------------------------------------------------------
   |
   | The payment processor classes and aliases
   |
   */

    'processors' => [
        // Add payment processor adapters as needed
        'fincra' => \App\Services\FincraAdapter::class,
        'payoneer' => \App\Services\PayoneerAdapter::class,
        'paystack' => \App\Services\PaystackAdapter::class
    ],
```


- In your controller or class of your choice, make use of the `Blinqpay` Facade

```php
public function __invoke(MakePaymentRequest $request)
    {
        try {
            $response = Blinqpay::performTransaction($request->validated());
            // Handle the response
        } catch (BlinqpayException $e) {
            // Handle BlinqpayException
        } catch (\Exception $e) {
            // Handle other exceptions
        }
    }
```