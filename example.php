<?php

// Proper library format - only Composer autoloader needed
require_once 'vendor/autoload.php';

use PayAgency\{PayAgencyApi, PayAgencyClientOptions};

try {
    // Initialize the client options
    $options = new PayAgencyClientOptions(
        '89ca59fb3b49ada55851021df12cfbc5',
        'PA_TEST_94bf3520bcbe435f2ed558c31ac664f3e72dfa3114a3232e436e25f9', // Use PA_LIVE_ for production
        'https://api.pay.agency' // Optional base URL
    );

    // Create the PayAgency API instance
    $payAgency = new PayAgencyApi($options);

    echo "=== PayAgency PHP SDK Example ===\n\n";

    // Example 1: S2S Payment
    echo "1. Testing S2S Payment:\n";
    $s2sData = [
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
        'webhook_url' => 'https://pay.agency/webhook',
        // 'order_id' => '12524AGSDF34DS9',
        'terminal_id' => 'T12345',
    ];

    $payment = $payAgency->getPayment();
    $result = $payment->S2S($s2sData);
    echo "S2S Result: " . json_encode($result, JSON_PRETTY_PRINT) . "\n\n";

    // Example 2: Hosted Payment
    echo "2. Testing Hosted Payment:\n";
    $hostedData = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
        'address' => '123 Main Street',
        'country' => 'GB',
        'city' => 'London',
        'state' => 'GB',
        'zip' => 'SW1A 1AA',
        'ip_address' => '127.0.0.1',
        'phone_number' => '7654233212',
        'amount' => 200,
        'currency' => 'GBP',
        'redirect_url' => 'https://yoursite.com/return',
        'webhook_url' => 'https://yoursite.com/webhook',
        'terminal_id' => 'T12345',
    ];

    $hostedResult = $payment->hosted($hostedData);
    echo "Hosted Result: " . json_encode($hostedResult, JSON_PRETTY_PRINT) . "\n\n";

    // Example 3: APM Payment
    echo "3. Testing APM Payment:\n";
    $apmData = [
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jane.smith@example.com',
        'address' => '456 Oak Avenue',
        'country' => 'GB',
        'city' => 'Manchester',
        'state' => 'GB',
        'zip' => 'M1 1AA',
        'ip_address' => '127.0.0.1',
        'phone_number' => '7654233213',
        'amount' => 150,
        'currency' => 'GBP',
        'payment_method' => 'paypal',
        'redirect_url' => 'https://yoursite.com/return',
        'webhook_url' => 'https://yoursite.com/webhook',
        'terminal_id' => 'T12345',
    ];

    $apmResult = $payment->APM($apmData);
    echo "APM Result: " . json_encode($apmResult, JSON_PRETTY_PRINT) . "\n\n";

    // Example 4: Payout Operations
    echo "4. Testing Payout Operations:\n";
    $payout = $payAgency->getPayout();

    // 4a. Get Wallets
    echo "4a. Getting Wallets:\n";
    $walletsResult = $payout->getWallets();
    echo "Wallets: " . json_encode($walletsResult, JSON_PRETTY_PRINT) . "\n\n";

    // 4b. Estimate Fee
    echo "4b. Estimating Payout Fee:\n";
    $estimateData = [
        'amount' => 100.00,
        'currency' => 'GBP',
        'destination_currency' => 'USD'
    ];
    $estimateResult = $payout->estimate_fee($estimateData);
    echo "Fee Estimate: " . json_encode($estimateResult, JSON_PRETTY_PRINT) . "\n\n";

    // 4c. Create Payout (this would fail with test credentials)
    try {
        echo "4c. Creating Payout:\n";
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
            'description' => 'Test payout',
            'terminal_id' => 'T12345',
        ];
        $payoutResult = $payout->payout($payoutData);
        echo "Payout Result: " . json_encode($payoutResult, JSON_PRETTY_PRINT) . "\n\n";

        // 4d. Check Payout Status (only if payout was created)
        if (isset($payoutResult['transaction_id'])) {
            echo "4d. Checking Payout Status:\n";
            $statusResult = $payout->payout_status($payoutResult['transaction_id']);
            echo "Payout Status: " . json_encode($statusResult, JSON_PRETTY_PRINT) . "\n";
        }
    } catch (Exception $payoutException) {
        echo "Payout creation failed (expected with test credentials): " . $payoutException->getMessage() . "\n";
    }

    // Example 5: Payment Link Operations
    echo "\n5. Testing Payment Link Operations:\n";
    $paymentLink = $payAgency->getPaymentLink();

    // 5a. Get Payment Templates
    echo "5a. Getting Payment Templates:\n";
    $templatesResult = $paymentLink->getTemplates();
    echo "Templates: " . json_encode($templatesResult, JSON_PRETTY_PRINT) . "\n\n";

    // 5b. Create Payment Link
    echo "5b. Creating Payment Link:\n";
    $paymentLinkData = [
        'amount' => 250,
        'currency' => 'GBP',
        'description' => 'Test Payment Link',
        'customer_email' => 'customer@example.com',
        'success_url' => 'https://yoursite.com/success',
        'cancel_url' => 'https://yoursite.com/cancel',
        'webhook_url' => 'https://yoursite.com/webhook',
        'expires_at' => date('Y-m-d H:i:s', strtotime('+24 hours')),
    ];

    try {
        $paymentLinkResult = $paymentLink->create($paymentLinkData);
        echo "Payment Link Result: " . json_encode($paymentLinkResult, JSON_PRETTY_PRINT) . "\n";
    } catch (Exception $paymentLinkException) {
        echo "Payment Link creation failed: " . $paymentLinkException->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    // echo "This is expected when using placeholder API keys.\n";
    // echo "Replace the keys with your actual PayAgency credentials to test real payments.\n";
}
