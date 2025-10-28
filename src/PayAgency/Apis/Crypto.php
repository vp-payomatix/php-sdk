<?php

namespace PayAgency\Apis;

use PayAgency\Apis\BaseApi;

class Crypto extends BaseApi
{
    /**
     * Create crypto onramp payment link
     */
    public function on_ramp_link($data)
    {
        return $this->payment_link(array_merge($data, [
            'transaction_type' => 'ONRAMP'
        ]));
    }

    /**
     * Create crypto offramp payment link
     */
    public function off_ramp_link($data)
    {
        return $this->payment_link(array_merge($data, [
            'transaction_type' => 'OFFRAMP'
        ]));
    }

    /**
     * Create crypto payin payment link
     */
    public function payin_link($data)
    {
        return $this->payment_link(array_merge($data, [
            'transaction_type' => 'PAYIN'
        ]));
    }

    /**
     * Create crypto onramp payment
     */
    public function on_ramp($data)
    {
        return $this->payment(array_merge($data, [
            'transaction_type' => 'ONRAMP'
        ]));
    }

    /**
     * Create crypto offramp payment
     */
    public function off_ramp($data)
    {
        return $this->payment(array_merge($data, [
            'transaction_type' => 'OFFRAMP'
        ]));
    }

    /**
     * Get crypto currencies
     */
    public function currencies($data)
    {
        try {
            $endpoints = [
                'test' => '/api/v1/test/crypto/currencies',
                'live' => '/api/v1/live/crypto/currencies'
            ];

            $response = $this->apiClient->request('POST', $endpoints[$this->env], [
                'json' => $data,
                'skip_encryption' => true
            ]);

            return $response;
        } catch (\Exception $e) {
            return $this->handleError($e, 'fetch crypto currencies');
        }
    }

    /**
     * Create crypto payin
     */
    public function payin($data)
    {
        try {
            $endpoints = [
                'test' => '/api/v1/test/crypto/payin',
                'live' => '/api/v1/live/crypto/payin'
            ];

            $response = $this->apiClient->request('POST', $endpoints[$this->env], [
                'json' => $data
            ]);

            return $response;
        } catch (\Exception $e) {
            return $this->handleError($e, 'crypto payin');
        }
    }

    /**
     * Create crypto payment link
     */
    public function payment_link($data)
    {
        try {
            $endpoints = [
                'test' => '/api/v1/crypto/payment-link',
                'live' => '/api/v1/crypto/payment-link'
            ];

            $response = $this->apiClient->request('POST', $endpoints[$this->env], [
                'json' => $data,
                'skip_encryption' => true
            ]);

            return $response;
        } catch (\Exception $e) {
            return $this->handleError($e, 'create crypto payment link');
        }
    }

    /**
     * Create crypto payment (onramp/offramp)
     */
    public function payment($data)
    {
        try {
            $endpoints = [
                'test' => '/api/v1/test/crypto',
                'live' => '/api/v1/live/crypto'
            ];

            $response = $this->apiClient->request('POST', $endpoints[$this->env], [
                'json' => $data
            ]);

            return $response;
        } catch (\Exception $e) {
            return $this->handleError($e, 'create crypto payment');
        }
    }
}
