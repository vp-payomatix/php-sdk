<?php

/**
 * PayAgency PHP SDK - Comprehensive Examples
 * 
 * This example demonstrates all PayAgency API capabilities including:
 * - Card Payments (S2S, Hosted, APM)
 * - Cryptocurrency transactions (OnRamp, OffRamp, PayIn)
 * - Payouts and wallet management
 * - Payment links
 * - Transaction history
 * - Refunds
 */

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

    echo "=== PayAgency PHP SDK - Comprehensive Examples ===\n\n";

    // === PAYMENT EXAMPLES ===
    
    // Example 1: Server-to-Server (S2S) Card Payment
    echo "1. Server-to-Server (S2S) Card Payment:\n";
    $s2sResult = $payAgency->getPayment()->S2S([
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
        // 'order_id' => 'ORDER_123', // optional
        'terminal_id' => 'T12345', // optional
    ]);
    echo "S2S Result: " . json_encode($s2sResult, JSON_PRETTY_PRINT) . "\n\n";

    // Example 2: Hosted Payment
    echo "2. Hosted Payment:\n";
    $hostedResult = $payAgency->getPayment()->hosted([
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
        // 'order_id' => 'ORDER_123', // optional
        'terminal_id' => 'T12345', // optional
    ]);
    echo "Hosted Result: " . json_encode($hostedResult, JSON_PRETTY_PRINT) . "\n\n";

    // Example 3: Alternative Payment Methods (APM)
    echo "3. Alternative Payment Methods (APM):\n";
    $apmResult = $payAgency->getPayment()->APM([
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
        // 'order_id' => 'ORDER_123', // optional
        'terminal_id' => 'T12345', // optional
    ]);
    echo "APM Result: " . json_encode($apmResult, JSON_PRETTY_PRINT) . "\n\n";

    // === PAYOUT EXAMPLES ===
    
    // Example 4: Get Wallets
    echo "4. Get Wallets:\n";
    $wallets = $payAgency->getPayout()->getWallets();
    echo "Wallets: " . json_encode($wallets, JSON_PRETTY_PRINT) . "\n\n";

    // Example 5: Estimate Payout Fee
    echo "5. Estimate Payout Fee:\n";
    $feeEstimate = $payAgency->getPayout()->estimate_fee([
        'wallet_id' => 'WAL7825818519632620',
        'amount' => 200,
        'card_number' => '4111111111111111',
    ]);
    echo "Fee Estimate: " . json_encode($feeEstimate, JSON_PRETTY_PRINT) . "\n\n";

    // Example 6: Create Payout
    echo "6. Create Payout:\n";
    try {
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
            // 'order_id' => 'ORDER_123', // optional
            'terminal_id' => 'T12345', // optional
        ]);
        echo "Payout Result: " . json_encode($payout, JSON_PRETTY_PRINT) . "\n\n";

        // Example 7: Check Payout Status
        if (isset($payout['data']['transaction_id'])) {
            echo "7. Check Payout Status:\n";
            $status = $payAgency->getPayout()->payout_status($payout['data']['transaction_id']);
            echo "Payout Status: " . json_encode($status, JSON_PRETTY_PRINT) . "\n\n";
        }
    } catch (Exception $e) {
        echo "Payout failed (expected): " . $e->getMessage() . "\n\n";
    }

    // === PAYMENT LINK EXAMPLES ===
    
    // Example 8: Get Payment Templates
    echo "8. Get Payment Templates:\n";
    try {
        $templates = $payAgency->getPaymentLink()->getTemplates();
        echo "Templates: " . json_encode($templates, JSON_PRETTY_PRINT) . "\n\n";
    } catch (Exception $e) {
        echo "Templates failed: " . $e->getMessage() . "\n\n";
    }

    // Example 9: Create Payment Link
    echo "9. Create Payment Link:\n";
    try {
        $paymentLink = $payAgency->getPaymentLink()->create([
            'payment_template_id' => 'PLI07435325281394735', // Required
            'amount' => 1000, // optional
            'currency' => 'USD', // optional
            'expiry_date' => '2024-12-31', // optional
            'terminal_id' => 'T12345', // optional
            // 'order_id' => 'ORDER_123', // optional
        ]);
        echo "Payment Link Result: " . json_encode($paymentLink, JSON_PRETTY_PRINT) . "\n\n";
    } catch (Exception $e) {
        echo "Payment Link creation failed: " . $e->getMessage() . "\n\n";
    }

    // === TRANSACTION EXAMPLES ===
    
    // Example 10: Get Transactions
    echo "10. Get Transactions:\n";
    try {
        $transactions = $payAgency->getTXN()->transactions([
            'transaction_start_date' => '2023-01-01', // optional
            'transaction_end_date' => '2023-12-31', // optional
            'nextCursor' => 'cursor_value', // optional - for pagination
            'prevCursor' => 'cursor_value', // optional - for pagination
        ]);
        echo "Transactions: " . json_encode($transactions, JSON_PRETTY_PRINT) . "\n\n";
    } catch (Exception $e) {
        echo "Transactions failed: " . $e->getMessage() . "\n\n";
    }

    // Example 11: Get Wallet Transactions
    echo "11. Get Wallet Transactions:\n";
    try {
        $walletTransactions = $payAgency->getTXN()->wallet_transaction([
            'transaction_start_date' => '2023-01-01', // optional
            'transaction_end_date' => '2023-12-31', // optional
            'nextCursor' => 'cursor_value', // optional - for pagination
            'prevCursor' => 'cursor_value', // optional - for pagination
        ]);
        echo "Wallet Transactions: " . json_encode($walletTransactions, JSON_PRETTY_PRINT) . "\n\n";
    } catch (Exception $e) {
        echo "Wallet Transactions failed: " . $e->getMessage() . "\n\n";
    }

    // Example 12: Get Transaction Status
    echo "12. Get Transaction Status:\n";
    $transactionId = $s2sResult['data']['transaction_id'] ?? 'PA6184044284539338';
    try {
        $status = $payAgency->getTXN()->status($transactionId);
        echo "Transaction Status: " . json_encode($status, JSON_PRETTY_PRINT) . "\n\n";
    } catch (Exception $e) {
        echo "Transaction Status failed: " . $e->getMessage() . "\n\n";
    }

    // === REFUND EXAMPLES ===
    
    // Example 13: Create Refund
    echo "13. Create Refund:\n";
    $transactionIdForRefund = $s2sResult['data']['transaction_id'] ?? 'PA6184044284539338';
    try {
        $refund = $payAgency->getRefund()->create([
            'reason' => 'Customer request',
            'transaction_id' => $transactionIdForRefund,
            'amount' => 25.00, // Optional - partial refund amount
            'currency' => 'GBP', // Optional
            'refund_type' => 'partial', // Optional
            'description' => 'Partial refund for damaged item' // Optional
        ]);
        echo "Refund Result: " . json_encode($refund, JSON_PRETTY_PRINT) . "\n\n";
    } catch (Exception $e) {
        echo "Refund failed: " . $e->getMessage() . "\n\n";
    }

    // === CRYPTOCURRENCY EXAMPLES ===
    
    // Example 14: Get Supported Currencies
    echo "14. Get Supported Currencies:\n";
    try {
        $currencies = $payAgency->getCrypto()->currencies([
            'country' => 'GB', // ISO 3166-1 alpha-2 country code
            'amount' => 100,
        ]);
        echo "Crypto Currencies: " . json_encode($currencies, JSON_PRETTY_PRINT) . "\n\n";
    } catch (Exception $e) {
        echo "Crypto currencies failed: " . $e->getMessage() . "\n\n";
    }

    // Example 15: Full-Featured Payment Method (OnRamp)
    echo "15. Full-Featured Crypto Payment (OnRamp):\n";
    try {
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
            // 'order_id' => 'ORDER_123', // optional
            'terminal_id' => 'T12345', // optional
        ]);
        echo "Crypto Payment (OnRamp): " . json_encode($cryptoPayment, JSON_PRETTY_PRINT) . "\n\n";
    } catch (Exception $e) {
        echo "Crypto payment failed: " . $e->getMessage() . "\n\n";
    }

    // Example 16: Full-Featured Payment Link Method
    echo "16. Full-Featured Crypto Payment Link:\n";
    try {
        $cryptoPaymentLink = $payAgency->getCrypto()->payment_link([
            'transaction_type' => 'ONRAMP', // or 'OFFRAMP' or 'PAYIN'
            'fiat_amount' => 100, // Required for ONRAMP and PAYIN
            // 'crypto_amount' => '0.01', // Required for OFFRAMP
            'fiat_currency' => 'GBP',
            'crypto_currency' => 'BTC',
            'payment_template_id' => 'PLI07435325281394735',
            // 'order_id' => 'ORDER_123', // optional
            'terminal_id' => 'T12345', // optional
            'expiry_date' => '2024-12-31', // optional
        ]);
        echo "Crypto Payment Link: " . json_encode($cryptoPaymentLink, JSON_PRETTY_PRINT) . "\n\n";
    } catch (Exception $e) {
        echo "Crypto payment link failed: " . $e->getMessage() . "\n\n";
    }

    // Example 17: OnRamp (Fiat to Crypto) Payment Link
    echo "17. OnRamp Payment Link:\n";
    try {
        $onRampLink = $payAgency->getCrypto()->on_ramp_link([
            'fiat_amount' => 100,
            'fiat_currency' => 'GBP',
            'crypto_currency' => 'BTC',
            'payment_template_id' => 'PLI07435325281394735',
            // 'order_id' => 'ORDER_123', // optional
            'terminal_id' => 'T12345', // optional
            'expiry_date' => '2024-12-31', // optional
        ]);
        echo "OnRamp Link: " . json_encode($onRampLink, JSON_PRETTY_PRINT) . "\n\n";
    } catch (Exception $e) {
        echo "OnRamp link failed: " . $e->getMessage() . "\n\n";
    }

    // Example 18: Direct OnRamp Transaction
    echo "18. Direct OnRamp Transaction:\n";
    try {
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
            // 'order_id' => 'ORDER_123', // optional
            'terminal_id' => 'T12345', // optional
        ]);
        echo "OnRamp Transaction: " . json_encode($onRamp, JSON_PRETTY_PRINT) . "\n\n";
    } catch (Exception $e) {
        echo "OnRamp transaction failed: " . $e->getMessage() . "\n\n";
    }

    // Example 19: OffRamp (Crypto to Fiat) Payment Link
    echo "19. OffRamp Payment Link:\n";
    try {
        $offRampLink = $payAgency->getCrypto()->off_ramp_link([
            'fiat_currency' => 'GBP',
            'crypto_currency' => 'BTC',
            'crypto_amount' => '0.01',
            'payment_template_id' => 'PLI07435325281394735',
            // 'order_id' => 'ORDER_123', // optional
            'terminal_id' => 'T12345', // optional
            'expiry_date' => '2024-12-31', // optional
        ]);
        echo "OffRamp Link: " . json_encode($offRampLink, JSON_PRETTY_PRINT) . "\n\n";
    } catch (Exception $e) {
        echo "OffRamp link failed: " . $e->getMessage() . "\n\n";
    }

    // Example 20: Direct OffRamp Transaction
    echo "20. Direct OffRamp Transaction:\n";
    try {
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
            // 'order_id' => 'ORDER_123', // optional
            'terminal_id' => 'T12345', // optional
        ]);
        echo "OffRamp Transaction: " . json_encode($offRamp, JSON_PRETTY_PRINT) . "\n\n";
    } catch (Exception $e) {
        echo "OffRamp transaction failed: " . $e->getMessage() . "\n\n";
    }

    // Example 21: Create PayIn Link
    echo "21. Create PayIn Link:\n";
    try {
        $payinLink = $payAgency->getCrypto()->payin_link([
            'fiat_amount' => 150,
            'fiat_currency' => 'USD',
            'crypto_currency' => 'BTC',
            'payment_template_id' => 'PLI07435325281394735',
            // 'order_id' => 'ORDER_123', // optional
            'terminal_id' => 'T12345', // optional
            'expiry_date' => '2024-12-31', // optional
        ]);
        echo "PayIn Link: " . json_encode($payinLink, JSON_PRETTY_PRINT) . "\n\n";
    } catch (Exception $e) {
        echo "PayIn link failed: " . $e->getMessage() . "\n\n";
    }

    // Example 22: Direct Crypto PayIn
    echo "22. Direct Crypto PayIn:\n";
    try {
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
            // 'order_id' => 'ORDER_123', // optional
            'terminal_id' => 'T12345', // optional
        ]);
        echo "Crypto PayIn: " . json_encode($payin, JSON_PRETTY_PRINT) . "\n\n";
    } catch (Exception $e) {
        echo "Crypto payin failed: " . $e->getMessage() . "\n\n";
    }

    echo "=== PayAgency PHP SDK Examples Complete ===\n";
    echo "All examples executed successfully!\n";
    echo "Note: Some operations may fail with test credentials, which is expected.\n";
    echo "Replace the API keys with your actual PayAgency credentials for live testing.\n";

} catch (Exception $e) {
    echo "Fatal Error: " . $e->getMessage() . "\n";
    echo "This is expected when using test credentials.\n";
    echo "Replace the keys with your actual PayAgency credentials to test real operations.\n";
}
