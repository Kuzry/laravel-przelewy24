<?php

use Kuzry\Przelewy24\Enums\Country;
use Kuzry\Przelewy24\Enums\Currency;
use Kuzry\Przelewy24\Enums\Environment;
use Kuzry\Przelewy24\Enums\Language;

return [

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    |
    | Defines which environment the Przelewy24 integration should use.
    |
    | Available values:
    | - sandbox – test mode, allows you to simulate payments and verify
    | the integration without processing real transactions.
    |
    | - production – live mode, used for handling real customer payments.
    |
    */

    'environment' => Environment::from(env('PRZELEWY24_POS_DEFAULT_ENVIRONMENT', Environment::SANDBOX->value)),

    /*
    |--------------------------------------------------------------------------
    | Auto Register Frontend Data
    |--------------------------------------------------------------------------
    |
    | Indicates whether the necessary Przelewy24 data for the frontend
    | should be automatically registered and made available on the
    | client side (e.g. routes, configuration, public keys).
    |
    | When enabled, the package will automatically share this data
    | with the frontend using Inertia.js:
    |
    |   Inertia::share('przelewy24', Przelewy24::getFrontendData());
    |
    | This allows frontend components to access required Przelewy24
    | configuration without any manual setup.
    |
    */

    'auto_register_frontend_data' => true,

    /*
    |--------------------------------------------------------------------------
    | POS (Point of Sale) Configurations
    |--------------------------------------------------------------------------
    |
    | This section allows you to define multiple POS configurations for
    | different payment scenarios or business entities. Each POS can have
    | its own credentials, environment settings, and merchant details.
    |
    | You can define as many POS configurations as needed. The 'default'
    | configuration will be used when no specific POS is selected.
    |
    | Example multi-POS setup:
    | 'pos' => [
    |     'default' => [...],
    |     'pos_1' => [...],
    |     'pos_2' => [...],
    | ]
    |
    */

    'pos' => [

        /*
        |----------------------------------------------------------------------
        | Default POS Configuration
        |----------------------------------------------------------------------
        |
        | This is the primary POS configuration that will be used by default
        | throughout your application unless you explicitly specify a different
        | POS configuration.
        |
        | You can add additional POS configurations below by creating new
        | arrays with unique keys (e.g. 'pos_2', 'wholesale', 'subscription').
        | Each POS can have its own credentials while sharing the global
        | environment setting.
        |
        | Example:
        | 'default' => [...],
        | 'pos_2' => [
        |     'merchant_id' => env('PRZELEWY24_POS_SHOP_A_MERCHANT_ID'),
        |     'pos_id' => env('PRZELEWY24_POS_SHOP_A_POS_ID'),
        |     'report_key' => env('PRZELEWY24_POS_SHOP_A_REPORT_KEY'),
        |     'crc' => env('PRZELEWY24_POS_SHOP_A_CRC'),
        | ],
        |
        */

        'default' => [

            /*
            |--------------------------------------------------------------------------
            | Merchant ID
            |--------------------------------------------------------------------------
            |
            | Unique identifier assigned by Przelewy24 for your merchant account.
            | Used to authorize transactions.
            |
            */

            'merchant_id' => env('PRZELEWY24_POS_DEFAULT_MERCHANT_ID'),

            /*
            |--------------------------------------------------------------------------
            | POS ID
            |--------------------------------------------------------------------------
            |
            | Point of Sale identifier linked to your merchant account.
            | Usually the same as Merchant ID unless you have multiple POS.
            |
            */

            'pos_id' => env('PRZELEWY24_POS_DEFAULT_POS_ID'),

            /*
            |--------------------------------------------------------------------------
            | Report Key
            |--------------------------------------------------------------------------
            |
            | Additional key generated in your Przelewy24 panel.
            | Required to access and download transaction reports.
            |
            */

            'report_key' => env('PRZELEWY24_POS_DEFAULT_REPORT_KEY'),

            /*
            |--------------------------------------------------------------------------
            | CRC Key
            |--------------------------------------------------------------------------
            |
            | Secret security key (CRC) assigned in your Przelewy24 panel.
            | Used to sign and verify transactions.
            |
            */

            'crc' => env('PRZELEWY24_POS_DEFAULT_CRC'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    |
    | The currency in which the transaction will be processed.
    | Must match one of the supported currency codes (e.g. PLN, EUR).
    |
    */

    'currency' => Currency::PLN,

    /*
    |--------------------------------------------------------------------------
    | Payment Method (optional)
    |--------------------------------------------------------------------------
    |
    | If provided, forces the payment to use a specific method ID
    | (e.g. BLIK, bank transfer). Leave null to allow the customer
    | to choose from all available methods.
    |
    */

    'method' => null,

    /*
    |--------------------------------------------------------------------------
    | Return URL
    |--------------------------------------------------------------------------
    |
    | The URL or route name where the customer will be redirected after
    | completing the payment.
    | This can be either:
    | - a full URL (e.g. https://example.com/payment/return), or
    | - a Laravel route name (e.g. 'payment.url_return')
    |
    */

    'url_return' => 'thank-you',

    /*
    |--------------------------------------------------------------------------
    | Status URL (optional)
    |--------------------------------------------------------------------------
    |
    | The URL or route name of the where the webhook endpoint that will receive
    | asynchronous notifications from Przelewy24 about the final payment status.
    | This can be either:
    | - a full URL (e.g. https://example.com/payment/return), or
    | - a Laravel route name (e.g. 'payment.url_status')
    |
    */

    'url_status' => 'przelewy24.status',

    /*
    |--------------------------------------------------------------------------
    | Transaction Register URL
    |--------------------------------------------------------------------------
    |
    | The URL or route name used when registering a new transaction
    | in the Przelewy24 system.
    |
    | This value defines where your application will send the registration
    | request.
    |
    | This value can be either:
    | - a full URL (e.g. https://example.com/przelewy24/transaction/register), or
    | - a Laravel route name (e.g. 'przelewy24.transaction.register')
    |
    */

    'url_transaction_register' => 'przelewy24.transaction.register',

    /*
    |--------------------------------------------------------------------------
    | Time Limit (optional)
    |--------------------------------------------------------------------------
    |
    | The maximum allowed time (in minutes) to complete the payment.
    | After this time expires, the transaction will be automatically cancelled.
    |
    */

    'time_limit' => null,

    /*
    |--------------------------------------------------------------------------
    | Payment Channel (optional)
    |--------------------------------------------------------------------------
    |
    | Restricts available payment methods to a specific channel.
    | Example: Channel::ONLINE, Channel::MOBILE
    |
    */

    'channel' => null,

    /*
    |--------------------------------------------------------------------------
    | Wait For Result (optional)
    |--------------------------------------------------------------------------
    |
    | If enabled, the customer will remain on the payment page until
    | the transaction result is confirmed. Otherwise they are redirected
    | immediately and notified asynchronously.
    |
    */

    'wait_for_result' => null,

    /*
    |--------------------------------------------------------------------------
    | Encoding (optional)
    |--------------------------------------------------------------------------
    |
    | The character encoding for transmitted data (e.g. UTF-8).
    | Usually not required — leave null unless specifically needed.
    |
    */

    'encoding' => null,

    /*
    |--------------------------------------------------------------------------
    | Country
    |--------------------------------------------------------------------------
    |
    | The country code of the payer. Defaults to Poland (PL).
    |
    */

    'country' => Country::PL,

    /*
    |--------------------------------------------------------------------------
    | Language
    |--------------------------------------------------------------------------
    |
    | The language used on the payment page interface.
    | Defaults to Polish (PL).
    |
    */

    'language' => Language::PL,

    /*
    |--------------------------------------------------------------------------
    | Regulation Accept
    |--------------------------------------------------------------------------
    |
    | Whether you accept Przelewy24 terms and conditions on behalf
    | of the customer. Required if the customer is not explicitly
    | shown the regulations during checkout.
    |
    */

    'regulation_accept' => null,
];
