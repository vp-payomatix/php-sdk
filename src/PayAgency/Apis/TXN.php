<?php

namespace PayAgency\Apis;

use PayAgency\Apis\BaseApi;

class TXN extends BaseApi
{
    /**
     * Get transaction history
     */
    public function transactions($params = [])
    {
        try {
            $endpoints = [
                'test' => '/api/v1/test-transactions',
                'live' => '/api/v1/live-transactions'
            ];

            $response = $this->apiClient->request('GET', $endpoints[$this->env], [
                'query' => $params
            ]);

            if ($this->env === 'test') {
                // Return mock data for test environment
                return [
                    'status' => 'success',
                    'data' => [
                        'transactions' => [
                            [
                                'id' => 'txn_test_' . uniqid(),
                                'amount' => $params['amount'] ?? 1000,
                                'currency' => $params['currency'] ?? 'USD',
                                'status' => 'completed',
                                'type' => 'payment',
                                'created_at' => date('c'),
                                'customer_email' => $params['customer_email'] ?? 'test@example.com'
                            ]
                        ],
                        'pagination' => [
                            'total' => 1,
                            'per_page' => 10,
                            'current_page' => 1
                        ]
                    ]
                ];
            }

            return $response;
        } catch (\Exception $e) {
            return $this->handleError($e, 'transactions');
        }
    }

    /**
     * Get wallet transaction history
     */
    public function wallet_transaction($params = [])
    {
        try {
            $endpoints = [
                'test' => '/api/v1/test-wallet-transactions',
                'live' => '/api/v1/live-wallet-transactions'
            ];

            $response = $this->apiClient->request('GET', $endpoints[$this->env], [
                'query' => $params
            ]);

            if ($this->env === 'test') {
                // Return mock data for test environment
                return [
                    'status' => 'success',
                    'data' => [
                        'transactions' => [
                            [
                                'id' => 'wallet_txn_test_' . uniqid(),
                                'wallet_id' => $params['wallet_id'] ?? 'wallet_test_123',
                                'amount' => $params['amount'] ?? 500,
                                'currency' => $params['currency'] ?? 'USD',
                                'status' => 'completed',
                                'type' => 'wallet_transfer',
                                'created_at' => date('c'),
                                'description' => $params['description'] ?? 'Test wallet transaction'
                            ]
                        ],
                        'pagination' => [
                            'total' => 1,
                            'per_page' => 10,
                            'current_page' => 1
                        ]
                    ]
                ];
            }

            return $response;
        } catch (\Exception $e) {
            return $this->handleError($e, 'wallet_transaction');
        }
    }

    /**
     * Get transaction status by ID
     */
    public function status($id)
    {
        try {
            $endpoints = [
                'test' => "/api/test/status/{$id}",
                'live' => "/api/live/status/{$id}"
            ];

            $response = $this->apiClient->request('GET', $endpoints[$this->env]);

            if ($this->env === 'test') {
                // Return mock status data for test environment
                return [
                    'status' => 'success',
                    'data' => [
                        'transaction_id' => $id,
                        'status' => 'completed',
                        'amount' => 1000,
                        'currency' => 'USD',
                        'payment_method' => 'card',
                        'created_at' => date('c', strtotime('-1 hour')),
                        'completed_at' => date('c'),
                        'customer_info' => [
                            'email' => 'test@example.com',
                            'name' => 'Test Customer'
                        ]
                    ]
                ];
            }

            return $response;
        } catch (\Exception $e) {
            return $this->handleError($e, 'status');
        }
    }
}
