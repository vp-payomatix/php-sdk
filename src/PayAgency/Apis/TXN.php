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

            return $response;
        } catch (\Exception $e) {
            return $this->handleError($e, 'status');
        }
    }
}
