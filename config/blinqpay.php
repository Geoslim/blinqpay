<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Routing Rules
    |--------------------------------------------------------------------------
    |
    | Define the rules used to determine the best payment processor for each
    | transaction. Rules can be based on factors like transaction fee, reliability,
    | currency support, and other criteria.
    |
    */

    'routing_rules' => [
        'transaction_fee' => 0.6, // Weight for fee-based routing
        'reliability' => 0.4, // Weight for reliability-based routing
    ],

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
    ],

    /*
    |--------------------------------------------------------------------------
    | Supported Currencies
    |--------------------------------------------------------------------------
    |
    | The payment processor classes and aliases
    |
    */

    'supported_currencies' => ['NGN', 'USD', 'EUR', 'GBP', 'CAD'],

    /*
    |--------------------------------------------------------------------------
    | Default Processor
    |--------------------------------------------------------------------------
    |
    | The default payment processor to use if none of the routing rules apply.
    |
    */

    'default_processor' => env('BLINQPAY_DEFAULT_PROCESSOR'),
];
