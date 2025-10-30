# PayAgency PHP SDK

A comprehensive PHP SDK for PayAgency payment processing platform, supporting multiple payment methods including card payments, cryptocurrency transactions, payouts, and payment links.

## Table of Contents

- [Installation](#installation)
- [Quick Start](#quick-start)
- [Configuration](#configuration)
- [API Reference](#api-reference)
  - [Payment](#payment)
  - [Payout](#payout)
  - [Payment Links](#payment-links)
  - [Cryptocurrency](#cryptocurrency)
  - [Transactions](#transactions)
  - [Refunds](#refunds)
- [Error Handling](#error-handling)
- [Security](#security)
- [Environment](#environment)
- [License](#license)

## Installation

```bash
composer require payagency/api
```

## Requirements

- PHP 7.4 or higher
- Guzzle HTTP client

## Quick Start

```php
<?php

require_once 'vendor/autoload.php';

use PayAgency\{PayAgencyApi, PayAgencyClientOptions};

// Initialize the SDK with minimal configuration
$payAgency = new PayAgencyApi(new PayAgencyClientOptions(
    "89ca59fb3b49ada55851021df12cfbc5", // 32-character encryption key
    "PA_TEST_your-secret-key", // or PA_LIVE_ for production
    // baseUrl is optional - defaults to https://backend.pay.agency
));

// Or with custom base URL
$payAgency = new PayAgencyApi(new PayAgencyClientOptions(
    "89ca59fb3b49ada55851021df12cfbc5",
    "PA_TEST_your-secret-key",
    "https://CUSTOM_SUB_DOMAIN.pay.agency"
));

// Make a payment
$payment = $payAgency->getPayment()->S2S([
    'first_name' => 'James',
    'last_name' => 'Dean',
    'email' => 'james@gmail.com',
    'address' => '64 Hertingfordbury Rd',
    'country' => 'GB',
    'city' => 'Newport',
    'state' => 'GB',
    'zip' => 'TF10 8DF',
    'ip_address' => '127.0.0.1',
    'phone_number' => '7654233212',
    'amount' => 100,
    'currency' => 'GBP',
    'card_number' => '4111111111111111',
    'card_expiry_month' => '12',
    'card_expiry_year' => '2027',
    'card_cvv' => '029',
    'redirect_url' => 'https://pay.agency',
    'webhook_url' => 'https://pay.agency/webhook', // optional
    'terminal_id' => 'T12345', // optional
]);
```

## Configuration

### PayAgencyClientOptions

| Parameter       | Type   | Required | Description                                                   |
| --------------- | ------ | -------- | ------------------------------------------------------------- |
| `encryptionKey` | string | Yes      | 32-character encryption key for payload encryption            |
| `secretKey`     | string | Yes      | Your API secret key (PA_TEST for test, PA_LIVE for live)      |
| `baseUrl`       | string | No       | PayAgency API base URL (defaults to `https://backend.pay.agency`) |

### Environment Detection

The SDK automatically detects the environment based on your secret key:

- Keys starting with `PA_LIVE_` use live endpoints
- Keys starting with `PA_TEST_` use test endpoints

## API Reference

### Payment

The Payment module supports multiple payment methods:

#### Server-to-Server (S2S) Card Payments

```php
$result = $payAgency->getPayment()->S2S([
    'first_name' => 'James',
    'last_name' => 'Dean',
    'email' => 'james@gmail.com',
    'address' => '64 Hertingfordbury Rd',
    'country' => 'GB',
    'city' => 'Newport',
    'state' => 'GB',
    'zip' => 'TF10 8DF',
    'ip_address' => '127.0.0.1',
    'phone_number' => '7654233212',
    'amount' => 100,
    'currency' => 'GBP',
    'card_number' => '4111111111111111',
    'card_expiry_month' => '12',
    'card_expiry_year' => '2027',
    'card_cvv' => '029',
    'redirect_url' => 'https://pay.agency',
    'webhook_url' => 'https://pay.agency/webhook', // optional
    'order_id' => 'ORDER_123', // optional
    'terminal_id' => 'T12345', // optional
]);

// Response format:
[
    'status' => 'SUCCESS', // or 'REDIRECT' or 'FAILED'
    'message' => 'Transaction processed successfully!.',
    'data' => [
        'amount' => 100,
        'currency' => 'GBP',
        'order_id' => null,
        'transaction_id' => 'PA6184044284539338',
        'customer' => [
            'first_name' => 'James',
            'last_name' => 'Dean',
            'email' => 'james@gmail.com'
        ],
        'refund' => [
            'status' => false,
            'refund_date' => null
        ],
        'chargeback' => [
            'status' => false,
            'chargeback_date' => null
        ]
    ],
    'redirect_url' => 'https://...' // Present for REDIRECT status
]
```

#### Hosted Payment

```php
$hostedPayment = $payAgency->getPayment()->hosted([
    'first_name' => 'James',
    'last_name' => 'Dean',
    'email' => 'james@gmail.com',
    'address' => '64 Hertingfordbury Rd',
    'country' => 'GB',
    'city' => 'Newport',
    'state' => 'GB',
    'zip' => 'TF10 8DF',
    'ip_address' => '127.0.0.1',
    'phone_number' => '7654233212',
    'amount' => 100,
    'currency' => 'GBP',
    'redirect_url' => 'https://pay.agency',
    'webhook_url' => 'https://pay.agency/webhook', // optional
    'order_id' => 'ORDER_123', // optional
    'terminal_id' => 'T12345', // optional
]);

// Returns the same response format as S2S
```

#### Alternative Payment Methods (APM)

```php
$apmPayment = $payAgency->getPayment()->APM([
    'first_name' => 'James',
    'last_name' => 'Dean',
    'email' => 'james@gmail.com',
    'address' => '64 Hertingfordbury Rd',
    'country' => 'GB',
    'city' => 'Newport',
    'state' => 'GB',
    'zip' => 'TF10 8DF',
    'ip_address' => '127.0.0.1',
    'phone_number' => '7654233212',
    'amount' => 100,
    'currency' => 'GBP',
    'payment_method' => 'paypal', // APM specific
    'redirect_url' => 'https://pay.agency',
    'webhook_url' => 'https://pay.agency/webhook', // optional
    'order_id' => 'ORDER_123', // optional
    'terminal_id' => 'T12345', // optional
]);

// Returns the same response format as S2S
```

### Payout

Manage payouts and wallet operations:

#### Create Payout

```php
$payout = $payAgency->getPayout()->payout([
    'wallet_id' => 'WAL1234567890',
    'first_name' => 'James',
    'last_name' => 'Dean',
    'email' => 'james@gmail.com',
    'address' => '64 Hertingfordbury Rd',
    'country' => 'US',
    'city' => 'Newport',
    'state' => 'US',
    'zip' => 'TF10 8DF',
    'ip_address' => '127.0.0.1',
    'phone_number' => '7654233212',
    'amount' => 100,
    'currency' => 'USD',
    'card_number' => '4222222222222222',
    'card_expiry_month' => '10',
    'card_expiry_year' => '2030',
    'card_cvv' => '123',
    'webhook_url' => 'https://pay.agency/webhook', // optional
    'order_id' => 'ORDER_123', // optional
    'terminal_id' => 'T12345', // optional
]);

// Response format:
[
    'status' => 'SUCCESS', // or 'BLOCKED', 'PENDING'
    'message' => 'Transaction processed successfully!.',
    'data' => [
        'amount' => 100,
        'currency' => 'USD',
        'order_id' => null,
        'transaction_id' => 'PA1234567890',
        'customer' => [
            'first_name' => 'James',
            'last_name' => 'Dean',
            'email' => 'james@gmail.com'
        ]
    ],
    'redirect_url' => '...' // Present if required
]
```

#### Get Wallets

```php
// Get all wallets
$wallets = $payAgency->getPayout()->getWallets();
// or
$walletsData = $payAgency->getPayout()->get_wallets();

// Response format (test environment returns mock data):
[
    [
        'wallet_id' => 'WAL1234567890',
        'currency' => 'USD',
        'amount' => 10000,
        'payment_method' => 'card',
        'status' => 'Active'
    ],
    [
        'wallet_id' => 'WAL9876543210',
        'currency' => 'EUR',
        'amount' => 5000,
        'payment_method' => 'card',
        'status' => 'Inactive'
    ]
]
```

#### Estimate Payout Fee

```php
$feeEstimate = $payAgency->getPayout()->estimate_fee([
    'wallet_id' => 'WAL7825818519632620',
    'amount' => 200,
    'card_number' => '4111111111111111',
]);

// Response format (test environment returns mock data):
[
    'data' => [
        'amount_requried' => 211.5,
        'wallet_balance' => 1000,
        'total_fee' => 11.5
    ]
]
```

#### Check Payout Status

```php
$status = $payAgency->getPayout()->payout_status('PAYOUT_REFERENCE_123');

// Response format:
[
    'status' => 'SUCCESS', // or 'PENDING', 'FAILED'
    'message' => 'Payout completed successfully',
    'data' => [
        'amount' => 100,
        'currency' => 'USD',
        'order_id' => null,
        'transaction_id' => 'PA1234567890',
        'customer' => [
            'first_name' => 'James',
            'last_name' => 'Dean',
            'email' => 'james@gmail.com'
        ]
    ]
]
```

### Payment Links

````php
### Payment Links

Create and manage payment links:

#### Create Payment Link

```php
$paymentLink = $payAgency->getPaymentLink()->create([
    'payment_template_id' => 'PLI07435325281394735', // Required
    'amount' => 1000, // optional
    'currency' => 'USD', // optional
    'expiry_date' => '2024-12-31', // optional
    'terminal_id' => 'T12345', // optional
    'order_id' => 'ORDER_123', // optional
]);

// Response format:
[
    'message' => 'Payment link created successfully',
    'data' => 'https://front.pay.agency/pay/PAY_LINK_...'
]
````

#### Get Payment Templates

```php
$templates = $payAgency->getPaymentLink()->getTemplates();

// Response format:
[
    'data' => [
        [
            'template_id' => '1',
            'template_name' => 'Default Template',
            'payment_template_id' => 'PLI07435325281394735',
            'template_screenshot' => 'https://...',
            'redirect_url' => 'https://...',
            'webhook_url' => 'https://...'
        ]
    ]
]
```

### Cryptocurrency

Handle cryptocurrency transactions:

#### Comprehensive Methods

##### Full-Featured Payment Method

```php
// Full crypto payment method - handles both OnRamp and OffRamp based on transaction_type
$cryptoPayment = $payAgency->getCrypto()->payment([
    'transaction_type' => 'ONRAMP', // or 'OFFRAMP'
    'first_name' => 'Diana',
    'last_name' => 'Prince',
    'email' => 'diana@pay.agency',
    'phone_number' => '0123456789',
    'fiat_amount' => 200, // Required for ONRAMP, omit for OFFRAMP
    // 'crypto_amount' => '0.05', // Required for OFFRAMP, omit for ONRAMP
    'fiat_currency' => 'EUR',
    'crypto_currency' => 'BTC',
    'wallet_address' => '1BoatSLRHtKNngkdXEeobR76b53LETtpyT',
    'ip_address' => '127.0.0.1',
    'country' => 'GB',
    'crypto_network' => 'BITCOIN',
    'redirect_url' => 'https://pay.agency',
    'webhook_url' => 'https://pay.agency/webhook', // optional
    'order_id' => 'ORDER_123', // optional
    'terminal_id' => 'T12345', // optional
]);
```

##### Full-Featured Payment Link Method

```php
// Full crypto payment link method - handles OnRamp, OffRamp, and PayIn based on transaction_type
$cryptoPaymentLink = $payAgency->getCrypto()->payment_link([
    'transaction_type' => 'ONRAMP', // or 'OFFRAMP' or 'PAYIN'
    'fiat_amount' => 100, // Required for ONRAMP and PAYIN
    // 'crypto_amount' => '0.01', // Required for OFFRAMP
    'fiat_currency' => 'GBP',
    'crypto_currency' => 'BTC',
    'payment_template_id' => 'PLI07435325281394735',
    'order_id' => 'ORDER_123', // optional
    'terminal_id' => 'T12345', // optional
    'expiry_date' => '2024-12-31', // optional
]);

// Response format for payment links:
[
    'message' => 'Crypto payment link created successfully',
    'data' => 'https://front.pay.agency/pay/CRYPTO_LINK_...'
]
```

#### Individual Convenience Methods

##### OnRamp (Fiat to Crypto)

```php
// Create OnRamp payment link
$onRampLink = $payAgency->getCrypto()->on_ramp_link([
    'fiat_amount' => 100,
    'fiat_currency' => 'GBP',
    'crypto_currency' => 'BTC',
    'payment_template_id' => 'PLI07435325281394735',
    'order_id' => 'ORDER_123', // optional
    'terminal_id' => 'T12345', // optional
    'expiry_date' => '2024-12-31', // optional
]);

// Direct OnRamp transaction
$onRamp = $payAgency->getCrypto()->on_ramp([
    'first_name' => 'Diana',
    'last_name' => 'Prince',
    'email' => 'diana@pay.agency',
    'phone_number' => '0123456789',
    'fiat_amount' => 200,
    'fiat_currency' => 'EUR',
    'crypto_currency' => 'BTC',
    'wallet_address' => '1BoatSLRHtKNngkdXEeobR76b53LETtpyT',
    'ip_address' => '127.0.0.1',
    'country' => 'GB',
    'crypto_network' => 'BITCOIN',
    'redirect_url' => 'https://pay.agency',
    'webhook_url' => 'https://pay.agency/webhook', // optional
    'order_id' => 'ORDER_123', // optional
    'terminal_id' => 'T12345', // optional
]);

// Response format:
[
    'status' => 'REDIRECT', // or 'PENDING', 'FAILED'
    'message' => 'Crypto transaction initiated',
    'redirect_url' => 'https://...',
    'data' => [
        'transaction_id' => 'CRYPTO_123',
        'fiat' => 'EUR',
        'fiat_amount' => 200,
        'crypto' => 'BTC',
        'crypto_amount' => 0.005,
        'customer' => [
            'first_name' => 'Diana',
            'last_name' => 'Prince',
            'email' => 'diana@pay.agency'
        ]
    ]
]
```

##### OffRamp (Crypto to Fiat)

```php
// Create OffRamp payment link
$offRampLink = $payAgency->getCrypto()->off_ramp_link([
    'fiat_currency' => 'GBP',
    'crypto_currency' => 'BTC',
    'crypto_amount' => '0.01',
    'payment_template_id' => 'PLI07435325281394735',
    'order_id' => 'ORDER_123', // optional
    'terminal_id' => 'T12345', // optional
    'expiry_date' => '2024-12-31', // optional
]);

// Direct OffRamp transaction
$offRamp = $payAgency->getCrypto()->off_ramp([
    'first_name' => 'Ethan',
    'last_name' => 'Hunt',
    'email' => 'ethan@pay.agency',
    'phone_number' => '0123456789',
    'fiat_currency' => 'GBP',
    'crypto_currency' => 'BTC',
    'crypto_amount' => '0.05',
    'wallet_address' => '1BoatSLRHtKNngkdXEeobR76b53LETtpyT',
    'ip_address' => '127.0.0.1',
    'country' => 'GB',
    'crypto_network' => 'BITCOIN',
    'redirect_url' => 'https://pay.agency',
    'webhook_url' => 'https://pay.agency/webhook', // optional
    'order_id' => 'ORDER_123', // optional
    'terminal_id' => 'T12345', // optional
]);

// Returns the same response format as OnRamp
```

#### Crypto PayIn

```php
// Get supported currencies
$currencies = $payAgency->getCrypto()->currencies([
    'country' => 'GB', // ISO 3166-1 alpha-2 country code
    'amount' => 100,
]);

// Response format:
[
    'message' => 'Currencies fetched successfully',
    'data' => [
        [
            'name' => 'Bitcoin',
            'code' => 'BTC',
            'symbol' => '₿'
        ],
        [
            'name' => 'Ethereum',
            'code' => 'ETH',
            'symbol' => 'Ξ'
        ]
    ]
]

// Create PayIn link
$payinLink = $payAgency->getCrypto()->payin_link([
    'fiat_amount' => 150,
    'fiat_currency' => 'USD',
    'crypto_currency' => 'BTC',
    'payment_template_id' => 'PLI07435325281394735',
    'order_id' => 'ORDER_123', // optional
    'terminal_id' => 'T12345', // optional
    'expiry_date' => '2024-12-31', // optional
]);

// Direct crypto payin
$payin = $payAgency->getCrypto()->payin([
    'first_name' => 'Fiona',
    'last_name' => 'Gallagher',
    'email' => 'hello@gmail.com',
    'address' => '64 Hertingfordbury Rd',
    'phone_number' => '0123456789',
    'ip_address' => '127.0.0.1',
    'crypto_currency' => 'BTC',
    'amount' => 300,
    'currency' => 'USD',
    'crypto_network' => 'BITCOIN',
    'country' => 'US',
    'redirect_url' => 'https://pay.agency',
    'webhook_url' => 'https://pay.agency/webhook', // optional
    'order_id' => 'ORDER_123', // optional
    'terminal_id' => 'T12345', // optional
]);

// Response format:
[
    'status' => 'SUCCESS', // or 'PENDING', 'FAILED'
    'message' => 'Crypto payin processed',
    'redirect_url' => 'https://...',
    'data' => [
        'amount' => 300,
        'currency' => 'USD',
        'order_id' => null,
        'transaction_id' => 'CRYPTO_PAYIN_123',
        'customer' => [
            'first_name' => 'Fiona',
            'last_name' => 'Gallagher',
            'email' => 'hello@gmail.com'
        ],
        'crypto_currency' => 'BTC'
    ]
]
```

### Transactions

Query transaction history:

#### Get Transactions

```php
$transactions = $payAgency->getTXN()->transactions([
    'transaction_start_date' => '2023-01-01', // optional
    'transaction_end_date' => '2023-12-31', // optional
    'nextCursor' => 'cursor_value', // optional - for pagination
    'prevCursor' => 'cursor_value', // optional - for pagination
]);

// Response format:
[
    'message' => 'Transactions fetched successfully',
    'data' => [
        [
            'first_name' => 'James',
            'last_name' => 'Dean',
            'converted_amount' => '100',
            'converted_currency' => 'GBP',
            'transaction_id' => 'PA6184044284539338',
            'amount' => '100',
            'currency' => 'GBP',
            'status' => 'SUCCESS',
            'card_type' => 'VISA',
            'card_number' => '411111XXXXXX1111',
            'transaction_type' => 'CARD',
            'order_id' => null,
            'country' => 'GB',
            'email' => 'james@gmail.com',
            'created_at' => '2025-10-27T10:44:00.443Z',
            'transaction_date' => '2025-10-27T10:44:00.443Z',
            'chargeback_date' => null,
            'refund_date' => null,
            'suspicious_date' => null,
            'merchant_connector' => [
                'name' => 'Connector Name'
            ],
            'user' => [
                'name' => 'Nagesh',
                'user_kyc' => [
                    'name' => 'Payomatix'
                ]
            ]
        ]
    ],
    'meta' => [
        'hasNextPage' => true,
        'hasPreviousPage' => false,
        'nextCursor' => 'NjA5',
        'prevCursor' => 'NjMy',
        'totalCount' => 487
    ]
]
```

#### Get Wallet Transactions

```php
$walletTransactions = $payAgency->getTXN()->wallet_transaction([
    'transaction_start_date' => '2023-01-01', // optional
    'transaction_end_date' => '2023-12-31', // optional
    'nextCursor' => 'cursor_value', // optional - for pagination
    'prevCursor' => 'cursor_value', // optional - for pagination
]);

// Returns the same response format as transactions
```

#### Get Transaction Status

```php
$status = $payAgency->getTXN()->status('PA6184044284539338');

// Response format:
[
    'status' => 'success',
    'data' => [
        'transaction_id' => 'PA6184044284539338',
        'status' => 'SUCCESS',
        'amount' => 100,
        'currency' => 'GBP',
        'payment_method' => 'card',
        'created_at' => '2025-10-27T10:44:00.443Z',
        'completed_at' => '2025-10-27T10:44:00.443Z',
        'customer_info' => [
            'email' => 'james@gmail.com',
            'name' => 'James Dean'
        ]
    ]
]
```

### Refunds

Process refunds:

```php
// Direct refund method
$refund = $payAgency->getRefund()->create([
    'reason' => 'Customer request',
    'transaction_id' => 'PA6184044284539338',
    'amount' => 25.00, // Optional - partial refund amount
    'currency' => 'GBP', // Optional
    'refund_type' => 'partial', // Optional
    'description' => 'Partial refund for damaged item' // Optional
]);

// Response format:
[
    'status' => 'SUCCESS',
    'message' => 'Transaction marked as refunded.',
    'data' => [
        'amount' => 100,
        'currency' => 'GBP',
        'order_id' => null,
        'transaction_id' => 'PA6184044284539338',
        'customer' => [
            'first_name' => 'James',
            'last_name' => 'Dean',
            'email' => 'james@gmail.com'
        ],
        'refund' => [
            'status' => true,
            'refund_date' => '2025-10-27T10:44:00.443Z'
        ],
        'chargeback' => [
            'status' => false,
            'chargeback_date' => null
        ]
    ]
]
```

## Error Handling

The SDK throws exceptions for API errors. Always wrap your API calls in try-catch blocks:

```php
try {
    $payment = $payAgency->getPayment()->S2S($paymentData);
    echo "Payment successful: " . json_encode($payment, JSON_PRETTY_PRINT);
} catch (Exception $error) {
    if (method_exists($error, 'getResponse') && $error->getResponse()) {
        // Server responded with error status
        echo "Payment failed: " . $error->getResponse()->getBody();
    } else {
        // Network or other error
        echo "Error: " . $error->getMessage();
    }
}
```

## Security

### Encryption

The SDK automatically encrypts request payloads using AES-256-CBC encryption with your provided encryption key. Some endpoints (like payment links and refunds) skip encryption as needed.

### API Key Security

- Never expose your API keys in client-side code
- Use test keys (`PA_TEST_`) for development
- Use live keys (`PA_LIVE_`) only in production
- Rotate your keys regularly

### Best Practices

1. Store API keys in environment variables
2. Use HTTPS for all webhook URLs
3. Validate webhook signatures on your server
4. Implement proper error handling
5. Log transactions for auditing

## Environment

The SDK supports both test and live environments:

### Test Environment

- Use secret keys starting with `PA_TEST_`
- Returns mock data for certain endpoints (wallets, fee estimation)
- Safe for development and testing

### Live Environment

- Use secret keys starting with `PA_LIVE_`
- Processes real transactions
- Use only in production

### Important Notes

- **Payment amounts**: Use actual currency amounts (e.g., 1 for $1.00 or £1.00)
- **Crypto amounts**: For crypto, use string format for precise decimal values (e.g., "0.01" for Bitcoin)
- **Country codes**: Use ISO 3166-1 alpha-2 country codes (e.g., "GB", "US")
- **Currency codes**: Use ISO 4217 currency codes (e.g., "USD", "GBP", "EUR")
- **Crypto networks**: Use uppercase format (e.g., "BITCOIN", "ETHEREUM")
- **Card expiry years**: Use full 4-digit format (e.g., "2027", not "27")
- **Optional fields**: Fields marked as optional can be omitted from the payload

## License

This SDK is provided under the MIT License.

## Support

For support and documentation, please visit [PayAgency Documentation](https://docs.pay.agency) or contact support@pay.agency

---

**Version**: 1.0.0

**Repository**: [payagency-php-sdk](https://github.com/vp-payomatix/php-sdk)

````
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

### Transaction History (TXN)

```php
// Get TXN instance
$txn = $payAgency->getTXN();

// Get transaction history
$transactionsParams = [
    'page' => 1,
    'per_page' => 10,
    'currency' => 'USD',
    'status' => 'completed'
];
$transactions = $txn->transactions($transactionsParams);
echo json_encode($transactions, JSON_PRETTY_PRINT);

// Get wallet transaction history
$walletTransactionsParams = [
    'wallet_id' => 'wallet_test_123',
    'page' => 1,
    'per_page' => 10,
    'currency' => 'USD'
];
$walletTransactions = $txn->wallet_transaction($walletTransactionsParams);
echo json_encode($walletTransactions, JSON_PRETTY_PRINT);

// Get transaction status by ID
$transactionId = 'PA1234567890';
try {
    $status = $txn->status($transactionId);
    echo json_encode($status, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo "Transaction not found: " . $e->getMessage();
}
```

## License

This SDK is provided under the MIT License.
````
