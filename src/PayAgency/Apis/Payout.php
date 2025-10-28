<?php

namespace PayAgency\Apis;

use PayAgency\Apis\BaseApi;

/**
 * The Payout class handles all payout-related API calls.
 * It uses the custom ApiClient for automatic request encryption.
 */
class Payout extends BaseApi
{



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
        try {
            $endpoint = $this->getEndpoint('payout');
            $response = $this->apiClient->request('POST', $endpoint, ['json' => $data]);
            return $response;
        } catch (\Exception $e) {
            return $this->handleError($e, "Create payout");
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
            // Return mock data for test environment
            if ($this->env === 'test') {
                return [
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
                ];
            }
            
            $endpoint = $this->getEndpoint('wallets');
            $response = $this->apiClient->request('GET', $endpoint);
            return $response;
        } catch (\Exception $e) {
            return $this->handleError($e, "Fetch wallets");
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
            // Return mock data for test environment
            if ($this->env === 'test') {
                return [
                    'data' => [
                        'amount_requried' => 211.5,
                        'wallet_balance' => 1000,
                        'total_fee' => 11.5
                    ]
                ];
            }
            
            $endpoint = $this->getEndpoint('estimate_fee');
            $response = $this->apiClient->request('POST', $endpoint, ['json' => $payload]);
            return $response;
        } catch (\Exception $e) {
            return $this->handleError($e, "Estimate payout fee");
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
            $response = $this->apiClient->request('GET', $endpoint);
            return $response;
        } catch (\Exception $e) {
            return $this->handleError($e, "Fetch payout status");
        }
    }

}
