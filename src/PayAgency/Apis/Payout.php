<?php

namespace PayAgency\Apis;

use PayAgency\lib\ApiClient;
use GuzzleHttp\Exception\GuzzleException;

/**
 * The Payout class handles all payout-related API calls.
 * It uses the custom ApiClient for automatic request encryption.
 */
class Payout extends BaseApi
{

    /** @var array Test wallets data for demo purposes */
    private $testWallets = [
        [
            'id' => 'wallet_test_1',
            'name' => 'Test Wallet 1',
            'currency' => 'GBP',
            'balance' => 1000.00,
            'status' => 'active'
        ],
        [
            'id' => 'wallet_test_2', 
            'name' => 'Test Wallet 2',
            'currency' => 'USD',
            'balance' => 500.00,
            'status' => 'active'
        ]
    ];

    /** @var array Test estimate payout response for demo purposes */
    private $testEstimatePayoutResponse = [
        'estimated_fee' => 2.50,
        'currency' => 'GBP',
        'processing_time' => '1-3 business days',
        'exchange_rate' => 1.0
    ];



    /**
     * Maps the environment to the corresponding API endpoint.
     *
     * @param string $type The payout operation type.
     * @return string The correct endpoint URI.
     */
    private function getEndpoint(string $type): string
    {
        $endpoints = [
            'payout' => [
                'test' => '/api/v1/test/payout',
                'live' => '/api/v1/live/payout',
            ],
            'wallets' => [
                'test' => '/api/v1/wallet',
                'live' => '/api/v1/wallet',
            ],
            'estimate_fee' => [
                'test' => '/api/v1/wallet/estimate-payout',
                'live' => '/api/v1/wallet/estimate-payout',
            ],
            'payout_status' => [
                'test' => '/api/v1/test/payout/%s/status',
                'live' => '/api/v1/live/payout/%s/status',
            ],
        ];

        return $endpoints[$type][$this->env] ?? '';
    }

    /**
     * Create a payout.
     * Corresponds to the async payout(data: PayoutInput): Promise<PayoutOutput> method.
     *
     * @param array $data The payout input data.
     * @return array The payout output data.
     * @throws GuzzleException|\Exception
     */
    public function payout(array $data): array
    {
        $endpoint = $this->getEndpoint('payout');
        
        try {
            $response = $this->apiClient->post($endpoint, $data);
            return $response;
        } catch (GuzzleException $e) {
            $this->handleError($e, "Create payout");
        }
    }

    /**
     * Get wallets - getter method equivalent to TypeScript 'get wallets()'.
     *
     * @return array The wallets data.
     */
    public function getWallets(): array
    {
        return $this->get_wallets();
    }

    /**
     * Fetch available wallets.
     * Corresponds to the async get_wallets(): Promise<WalletsOutput> method.
     *
     * @return array The wallets output data.
     * @throws GuzzleException|\Exception
     */
    public function get_wallets(): array
    {
        try {
            $endpoint = $this->getEndpoint('wallets');
            
            // Return test data for test environment
            if ($this->env === 'test') {
                return [
                    'data' => $this->testWallets
                ];
            }
            
            $response = $this->apiClient->get($endpoint);
            return $response;
        } catch (GuzzleException $e) {
            $this->handleError($e, "Fetch wallets");
        }
    }

    /**
     * Estimate payout fee.
     * Corresponds to the async esitimate_fee(payload: EstimateFeeInput): Promise<EstimateFeeOutput> method.
     * Note: Fixed typo from "esitimate" to "estimate"
     *
     * @param array $payload The estimate fee input data.
     * @return array The estimate fee output data.
     * @throws GuzzleException|\Exception
     */
    public function estimate_fee(array $payload): array
    {
        try {
            $endpoint = $this->getEndpoint('estimate_fee');
            
            // Return test data for test environment
            if ($this->env === 'test') {
                return $this->testEstimatePayoutResponse;
            }
            
            $response = $this->apiClient->post($endpoint, $payload);
            return $response;
        } catch (GuzzleException $e) {
            $this->handleError($e, "Estimate payout fee");
        }
    }

    /**
     * Get payout status by reference ID.
     * Corresponds to the async payout_status(reference_id: string): Promise<PayoutStatusOutput> method.
     *
     * @param string $referenceId The payout reference ID.
     * @return array The payout status output data.
     * @throws GuzzleException|\Exception
     */
    public function payout_status(string $referenceId): array
    {
        try {
            $endpoint = sprintf($this->getEndpoint('payout_status'), $referenceId);
            
            $response = $this->apiClient->get($endpoint);
            return $response;
        } catch (GuzzleException $e) {
            $this->handleError($e, "Fetch payout status");
        }
    }

}
