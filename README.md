# PayAgency PHP SDK

A PHP SDK for the PayAgency payment processing API.

## Installation

Install via Composer:

```bash
composer require payagency/api
```

## Requirements

- PHP 7.4 or higher
- Guzzle HTTP client

## Usage

### Basic Setup

```php
<?php

require_once 'vendor/autoload.php';

use PayAgency\{PayAgencyApi, PayAgencyClientOptions};

// Initialize the client
$options = new PayAgencyClientOptions(
    'your-encryption-key',
    'PA_TEST_your-secret-key', // Use PA_LIVE_ for production
    'https://backend.pay.agency' // Optional base URL
);

$payAgency = new PayAgencyApi($options);
```

### S2S (Server-to-Server) Payments

```php
$paymentData = [
    'amount' => 1000, // Amount in cents
    'currency' => 'USD',
    'card' => [
        'number' => '4111111111111111',
        'exp_month' => '12',
        'exp_year' => '2025',
        'cvc' => '123'
    ],
    'customer' => [
        'email' => 'customer@example.com',
        'name' => 'John Doe'
    ]
];

try {
    $payment = $payAgency->getPayment();
    $result = $payment->S2S($paymentData);
    echo json_encode($result, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

### Hosted Payments

```php
$hostedData = [
    'amount' => 2000,
    'currency' => 'USD',
    'return_url' => 'https://yoursite.com/return',
    'customer' => [
        'email' => 'customer@example.com'
    ]
];

try {
    $payment = $payAgency->getPayment();
    $result = $payment->hosted($hostedData);
    echo json_encode($result, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

### Alternative Payment Methods (APM)

```php
$apmData = [
    'amount' => 1500,
    'currency' => 'USD',
    'payment_method' => 'paypal',
    'customer' => [
        'email' => 'customer@example.com'
    ]
];

try {
    $payment = $payAgency->getPayment();
    $result = $payment->APM($apmData);
    echo json_encode($result, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

### Payouts

```php
// Get payout instance
$payout = $payAgency->getPayout();

// Get available wallets
$wallets = $payout->getWallets();
echo json_encode($wallets, JSON_PRETTY_PRINT);

// Estimate payout fee
$estimateData = [
    'amount' => 100.00,
    'currency' => 'GBP',
    'destination_currency' => 'USD'
];
$estimate = $payout->estimate_fee($estimateData);

// Create a payout
$payoutData = [
    'first_name' => 'John',
    'last_name' => 'Doe',
    'email' => 'john.doe@example.com',
    'address' => '789 Payout Street',
    'country' => 'GB',
    'city' => 'Birmingham',
    'state' => 'GB',
    'zip' => 'B1 1AA',
    'ip_address' => '127.0.0.1',
    'phone_number' => '7654233214',
    'amount' => 50,
    'currency' => 'GBP',
    'card_number' => '4111111111111111',
    'card_expiry_month' => '12',
    'card_expiry_year' => '2027',
    'card_cvv' => '123',
    'wallet_id' => 'wallet_test_1',
    'terminal_id' => 'T12345',
];

try {
    $result = $payout->payout($payoutData);
    echo json_encode($result, JSON_PRETTY_PRINT);

    // Check payout status
    if (isset($result['transaction_id'])) {
        $status = $payout->payout_status($result['transaction_id']);
        echo json_encode($status, JSON_PRETTY_PRINT);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

### Payment Links

```php
// Get payment link instance
$paymentLink = $payAgency->getPaymentLink();

// Get available templates
$templates = $paymentLink->getTemplates();
echo json_encode($templates, JSON_PRETTY_PRINT);

// Create a payment link
$paymentLinkData = [
    'amount' => 250,
    'currency' => 'GBP',
    'description' => 'Product Purchase',
    'customer_email' => 'customer@example.com',
    'success_url' => 'https://yoursite.com/success',
    'cancel_url' => 'https://yoursite.com/cancel',
    'webhook_url' => 'https://yoursite.com/webhook',
    'expires_at' => date('Y-m-d H:i:s', strtotime('+24 hours')),
];

try {
    $result = $paymentLink->create($paymentLinkData);
    echo json_encode($result, JSON_PRETTY_PRINT);
    // Returns: {"message": "Payment link created successfully", "data": "https://front.pay.agency/pay/PAY_LINK_..."}
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

## Environment Detection

The SDK automatically detects the environment based on your secret key:

- Keys starting with `PA_TEST_` will use the test environment
- Keys starting with `PA_LIVE_` will use the live environment

## Error Handling

The SDK throws exceptions for API errors. Always wrap your API calls in try-catch blocks:

```php
try {
    $result = $payment->S2S($paymentData);
    // Handle success
} catch (GuzzleHttp\Exception\RequestException $e) {
    // Handle HTTP errors
    echo "Request failed: " . $e->getMessage();
} catch (Exception $e) {
    // Handle other errors
    echo "Error: " . $e->getMessage();
}
```

## Security

- All request payloads are automatically encrypted using AES-256-CBC
- API authentication is handled via Bearer token
- Always use HTTPS endpoints (enforced by the SDK)

## License

This SDK is provided under the MIT License.
