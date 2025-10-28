<?php

namespace PayAgency;

// PayAgency PHP SDK - Single Import File
// This file includes all necessary classes for the PayAgency SDK

require_once __DIR__ . '/lib/ApiClient.php';
require_once __DIR__ . '/Apis/BaseApi.php';
require_once __DIR__ . '/Apis/Payment.php';
require_once __DIR__ . '/Apis/Payout.php';
require_once __DIR__ . '/Apis/PaymentLink.php';
require_once __DIR__ . '/Apis/TXN.php';
require_once __DIR__ . '/Apis/Refund.php';
require_once __DIR__ . '/Apis/Crypto.php';

use PayAgency\lib\ApiClient;
use PayAgency\Apis\Payment;
use PayAgency\Apis\Payout;
use PayAgency\Apis\PaymentLink;
use PayAgency\Apis\TXN;
use PayAgency\Apis\Refund;
use PayAgency\Apis\Crypto;

/**
 * Configuration options for the PayAgency API client
 */
class PayAgencyClientOptions
{
    public string $encryptionKey;
    public string $secretKey;
    public ?string $baseUrl;

    public function __construct(string $encryptionKey, string $secretKey, ?string $baseUrl = null)
    {
        $this->encryptionKey = $encryptionKey;
        $this->secretKey = $secretKey;
        $this->baseUrl = $baseUrl;
    }

    public function toArray(): array
    {
        return [
            'encryptionKey' => $this->encryptionKey,
            'secretKey' => $this->secretKey,
            'baseUrl' => $this->baseUrl,
        ];
    }
}

/**
 * Main PayAgency API client
 */
class PayAgencyApi
{
    private ApiClient $client;
    private Payment $paymentInstance;
    private Payout $payoutInstance;
    private PaymentLink $paymentLinkInstance;
    private TXN $txnInstance;
    private Refund $refundInstance;
    private Crypto $cryptoInstance;

    /**
     * Helper method to create API module instances.
     *
     * @param string $apiClass The class name of the API module.
     * @return mixed
     */
    private function createApi(string $apiClass)
    {
        return new $apiClass($this->client, $this->client->getEnvironment());
    }

    /**
     * @param PayAgencyClientOptions $apiClientOptions Options to configure the client.
     */
    public function __construct(PayAgencyClientOptions $apiClientOptions)
    {
        // 1. Initialize the base client
        $this->client = new ApiClient($apiClientOptions->toArray());
        
        // 2. Initialize the API module instances
        $this->paymentInstance = $this->createApi(Payment::class);
        $this->payoutInstance = $this->createApi(Payout::class);
        $this->paymentLinkInstance = $this->createApi(PaymentLink::class);
        $this->txnInstance = $this->createApi(TXN::class);
        $this->refundInstance = $this->createApi(Refund::class);
        $this->cryptoInstance = $this->createApi(Crypto::class);
    }

    /**
     * Get the Payment API module instance.
     * @return Payment
     */
    public function getPayment(): Payment
    {
        return $this->paymentInstance;
    }

    /**
     * Get the Payout API module instance.
     * @return Payout
     */
    public function getPayout(): Payout
    {
        return $this->payoutInstance;
    }

    /**
     * Get the PaymentLink API module instance.
     * @return PaymentLink
     */
    public function getPaymentLink(): PaymentLink
    {
        return $this->paymentLinkInstance;
    }

    /**
     * Get the TXN API module instance.
     * @return TXN
     */
    public function getTXN(): TXN
    {
        return $this->txnInstance;
    }

    /**
     * Get the Refund API module instance.
     * @return Refund
     */
    public function getRefund(): Refund
    {
        return $this->refundInstance;
    }

    /**
     * Get the Crypto API module instance.
     * @return Crypto
     */
    public function getCrypto(): Crypto
    {
        return $this->cryptoInstance;
    }
}
