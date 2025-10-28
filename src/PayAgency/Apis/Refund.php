<?php

namespace PayAgency\Apis;

use PayAgency\Apis\BaseApi;

class Refund extends BaseApi
{
    /**
     * Create a refund for a transaction
     */
    public function create($data)
    {
        try {
            $endpoints = [
                'test' => '/api/v1/test/refund',
                'live' => '/api/v1/live/refund'
            ];

            $response = $this->apiClient->request('POST', $endpoints[$this->env], [
                'json' => $data,
                'skip_encryption' => true // Skip encryption for refund requests
            ]);

            return $response;
        } catch (\Exception $e) {
            return $this->handleError($e, 'create refund');
        }
    }

}
