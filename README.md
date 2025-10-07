# Laravel Przelewy24


[![Latest Version on Packagist](https://img.shields.io/packagist/v/kuzry/laravel-przelewy24.svg?style=flat-square)](https://packagist.org/packages/kuzry/laravel-przelewy24)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/kuzry/laravel-przelewy24/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/kuzry/laravel-przelewy24/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/kuzry/laravel-przelewy24/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/kuzry/laravel-przelewy24/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/kuzry/laravel-przelewy24.svg?style=flat-square)](https://packagist.org/packages/kuzry/laravel-przelewy24)

A Laravel package for seamless integration with [Przelewy24](https://www.przelewy24.pl/) payment gateway. Przelewy24 is one of the leading online payment providers in Poland, offering a wide range of payment methods including credit cards, bank transfers, BLIK, and more.

This package allows you to quickly implement secure online payments in your Laravel application using the official Przelewy24 API.

## Features

- 🚀 Easy Integration - Quick setup with minimal configuration
- ⚛️ React Hooks - Ready-to-use hooks for frontend payment handling
- 🏪 Multiple POS Support - Handle multiple Point of Sales configurations
- 🔄 Automatic Data Sharing - Seamless integration with Inertia.js
- 🔒 Secure - Built-in signature verification and transaction validation
- 📝 TypeScript Support - Full type definitions for frontend integration

## Requirements

- **PHP**: 8.3 or higher
- **Laravel**: 11.x or higher

## Installation

### 1. Install the package

```bash
composer require kuzry/laravel-przelewy24
```

### 2. Publish a configuration file

```bash
php artisan vendor:publish --tag=przelewy24-config
```

Default values from the configuration file can be overridden directly when calling the package's methods.

### 3. Configure environment variables

Add the following variables to your `.env` file. You can obtain these credentials from your Przelewy24 merchant panel:

```dotenv
PRZELEWY24_ENVIRONMENT=sandbox # sandbox or production
PRZELEWY24_MERCHANT_ID=        # your merchant ID from Przelewy24 panel
PRZELEWY24_POS_ID=             # your POS ID (usually same as merchant ID)
PRZELEWY24_CRC=                # CRC key from Przelewy24
PRZELEWY24_REPORT_KEY=         # report key used for transaction verification
```

**Note:** Always use a sandbox environment for development and testing. Switch to production only when you're ready to accept real payments.

### 4. Frontend Framework Setup

#### 4.1 React

Update your `vite.config.js`:

```javascript
import { resolve } from 'path';

export default defineConfig({
    resolve: {
        alias: {
            '@kuzry/laravel-przelewy24': resolve(__dirname, 'vendor/kuzry/laravel-przelewy24/resources/js/react'),
        }
    }
});
```

Update your `tsconfig.json`:

```json
{
    "compilerOptions": {
        "paths": {
            "@kuzry/laravel-przelewy24": ["./vendor/kuzry/laravel-przelewy24/resources/js/react"]
        }
    }
}
```

## Quick start

### Payment Flow Overview

The integration follows a standard payment flow:

1. **Register Transaction** - Create a payment session with Przelewy24
2. **Redirect Customer** - Send the customer to Przelewy24 payment gateway
3. **Receive Notification** - Handle the webhook callback after payment from Przelewy24
4. **Verify Transaction** - Confirm the payment with Przelewy24 API

### Transaction Registration

The `transactionRegister` method initiates a new payment transaction with Przelewy24. It creates a payment session and returns the data needed to redirect the customer to the payment gateway.

**Important security note:** Never trust data directly from the request for critical fields like `amount`, `currency` or `sessionId`. Always retrieve these values from your database or calculate them server-side to prevent payment manipulation.

```php
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Kuzry\Przelewy24\Facades\Przelewy24;

class Przelewy24Controller extends Controller
{
    public function transactionRegister(Request $request): JsonResponse
    {
        $order = Order::create([
            'number' => 'O-123456789-2025-01-01',
            'status' => 'pending',
            'products' => [1, 2],
        ]);

        return response()->json(
            Przelewy24::transaction()->register(
                TransactionRegisterRequestData::from([
                    'sessionId' => $order->number,
                    'amount' => $order->amount,
                    'description' => 'Order: ' . $order->number,
                    'email' => $request->input('email'),
                    'currency' => $order->currency,
                    'client' => $request->input('client'),
                    'address' => $request->input('address'),
                    'zip' => $request->input('zip'),
                    'city' => $request->input('city'),
                    'phone' => $request->input('phone'),
                    'method' => $request->input('method'),
                ]),
            )
        );
    }
}
```

Register the route in your `routes/web.php`:

```php
Route::post('/przelewy24/transaction/register', [Przelewy24Controller::class, 'transactionRegister'])
    ->name('przelewy24.transaction.register');
```

**Note:** The route name must match the `url_transaction_register` option in your `config/przelewy24.php` file. This allows the package to generate proper callback URLs.

### Payment Status Webhook

After the customer completes the payment, Przelewy24 will send a notification to your webhook endpoint. This is where you verify and confirm the transaction.

```php
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Kuzry\Przelewy24\Facades\Przelewy24;

class Przelewy24Controller extends Controller
{    
    public function statusWebhook(Request $request): JsonResponse
    {
       $order = Order::where('number', $request->input('sessionId'))
            ->firstOrFail();

        $data = TransactionRegisterNotificationData::from([
            'sessionId' => $order->number,
            'amount' => $request->input('amount'),
            'originAmount' => $order->amount,
            'currency' => $order->currency,
            'orderId' => $request->input('orderId'),
            'methodId' => $request->input('methodId'),
            'statement' => $request->input('statement'),
        ]);

        if (Przelewy24::notification()->isTransactionRegisterValid($request->input('sign'), $data)) {
            $transactionVerifyResponseData = Przelewy24::transaction()->verify(
                TransactionVerifyRequestData::fromRequest($request)
            );

            if ($transactionVerifyResponseData->isSuccess) {
                $order->update([
                    'status' => 'completed',
                ]);

                return response()->json([
                    'success' => true,
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => 'Verification failed',
            ], 400);
        }

        return response()->json([
            'success' => false,
            'error' => 'Invalid signature',
        ], 400);
    }
}
```

Register the webhook route in your `routes/api.php`:

```php
Route::post('/przelewy24/status', [Przelewy24Controller::class, 'statusWebhook'])
    ->name('przelewy24.status');
```

**Note:** The route name must match the `url_status` option in your `config/przelewy24.php` file. This allows the package to generate proper callback URLs.

### Frontend Integration

#### Automatic Data Sharing

If you're using Inertia.js, the package can automatically share Przelewy24 configuration data with all your frontend components. The przelewy24 prop will be available globally in every Inertia page without any additional setup.

To enable this feature, set the following option in your `config/przelewy24.php`:

```php
'auto_register_frontend_data' => true,
```

This is handled automatically by the package's Service Provider. No additional setup required!

#### Manual Data Sharing

If `auto_register_frontend_data` is disabled, you need to manually pass the data in your controllers:

```php
<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Kuzry\Przelewy24\Facades\Przelewy24;

Route::get('/cart', function () {
    return Inertia::render('cart', [
        'przelewy24' => Przelewy24::getFrontendData(),
    ]);
})->name('home');
```

### React Integration

The package provides a ready-to-use React hook (`usePrzelewy24`) for handling payment initialization and redirection directly from your frontend. This hook simplifies the integration process, allowing you to start a transaction in just a few lines of code.

#### Hook API

The `usePrzelewy24()` hook returns an object with the following structure:

```typescript
const przelewy24 = usePrzelewy24();

// Returns:
{
  transaction: {
    registerRequest: (options: RegisterRequestOptions) => Promise<void>
    isRegisterLoading: boolean
  }
}
```

#### Basic Usage

Here's a simple example of implementing a payment button in your shopping cart:

```typescript jsx
import { usePrzelewy24 } from '@kuzry/laravel-przelewy24';
import { Button } from '@mantine/core';
import { modals } from '@mantine/modals';

const ShoppingCart = () => {
    const przelewy24 = usePrzelewy24();

    const handlePayment = () => {
        przelewy24.transaction.registerRequest({
            data: {
                email: 'john.doe@example.com',
                client: 'John Doe',
                address: 'Fake Street 123',
                zip: '11-111',
                city: 'Warsaw',
                phone: '123 456 789',
                country: 'PL',
                language: 'pl',
            },
        });
    };

    return (
        <Button
            onClick={handlePayment}
            loading={przelewy24.transaction.isRegisterLoading}
        >
            Pay order
        </Button>
    );
}
```

By default, the hook automatically redirects the user to the Przelewy24 payment gateway on success.

#### Advanced Usage with Custom Callbacks

You can customize the behavior by providing `onSuccess` and `onError` callbacks:

```typescript jsx
import { usePrzelewy24 } from '@kuzry/laravel-przelewy24';
import { Button } from '@mantine/core';
import { modals } from '@mantine/modals';

const ShoppingCart = () => {
    const przelewy24 = usePrzelewy24();

    const handlePayment = () => {
        przelewy24.transaction.registerRequest({
            data: {
                email: 'john.doe@example.com',
                client: 'John Doe',
                address: 'Fake Street 123',
                zip: '11-111',
                city: 'Warsaw',
                phone: '123 456 789',
                country: 'PL',
                language: 'pl',
            },
            // You can pass "onSucces", but the default implementation will automatically redirect to redirectUrl.
            onSuccess: ({ redirectUrl }) => {
                window.location.href = redirectUrl;
            },
            onError: ({ errors }) => {
                const modal = modals.open({
                    title: 'Error',
                    children: (
                        <div className="flex flex-col gap-4">
                            {errors.map((error, key) => (
                                <div key={key}>{error}</div>
                            ))}
                        </div>
                    ),
                });
            },
        });
    };

    return (
        <Button
            onClick={handlePayment}
            loading={przelewy24.transaction.isRegisterLoading}
        >
            Pay order
        </Button>
    );
}
```

## Security

### Best Practices
- Always validate amounts and currencies server-side
- Use HTTPS in production
- Implement proper error handling
- Log all payment transactions
- Never expose your CRC or Report Key

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Krzysztof Kuzara](https://github.com/kuzry)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
