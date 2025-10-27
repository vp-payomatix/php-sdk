<?php

namespace PayAgency\Apis;

use PayAgency\lib\ApiClient;
use GuzzleHttp\Exception\GuzzleException;

// --- Interface/Type definitions (as PHP classes/interfaces/arrays) ---
// Note: In a real-world PHP app, you'd define proper classes for these types, 
// but for a simple client, we can treat them as arrays/stdClass for now.
// For example, if you were using an advanced framework like Symfony or Laravel:
// class S2SInput { /* ... properties ... */ }
// class S2SOutput { /* ... properties ... */ }

/**
 * The Payment class handles all payment-related API calls.
 * It uses the custom ApiClient for automatic request encryption.
 */
class Payment extends BaseApi
{

    /**
     * Maps the environment to the corresponding API endpoint.
     *
     * @param string $type The payment type ('card', 'hosted/card', 'apm').
     * @return string The correct endpoint URI.
     */
    private function getEndpoint(string $type): string
    {
        // Adjust endpoint mapping based on the provided TypeScript code structure
        $endpoints = [
            'S2S' => [
                'test' => '/api/v1/test/card',
                'live' => '/api/v1/live/card',
            ],
            'Hosted' => [
                'test' => '/api/v1/test/hosted/card',
                'live' => '/api/v1/live/hosted/card',
            ],
            'APM' => [
                'test' => '/api/v1/test/apm',
                'live' => '/api/v1/live/apm',
            ],
        ];

        return $endpoints[$type][$this->env] ?? '';
    }

    /**
     * Handles S2S Card Payments.
     * Corresponds to the async S2S(data: S2SInput): Promise<S2SOutput> method.
     *
     * @param array $data The payment input data (S2SInput).
     * @return array The payment output data (S2SOutput).
     * @throws GuzzleException| \Exception
     */
    public function S2S(array $data): array
    {
        $endpoint = $this->getEndpoint('S2S');
        
        try {
            $response = $this->apiClient->post($endpoint, $data);
            return $response;
        } catch (GuzzleException $e) {
            // Error handling equivalent to console.error + throw
            $this->handleError($e, "S2S payment");
        }
    }

    /**
     * Handles Hosted Card Payments.
     * Corresponds to the async hosted(data: HostedInput): Promise<HostedOutput> method.
     *
     * @param array $data The payment input data (HostedInput).
     * @return array The payment output data (HostedOutput).
     * @throws GuzzleException|\Exception
     */
    public function hosted(array $data): array
    {
        $endpoint = $this->getEndpoint('Hosted');
        
        try {
            $response = $this->apiClient->post($endpoint, $data);
            return $response;
        } catch (GuzzleException $e) {
            $this->handleError($e, "Hosted payment");
        }
    }

    /**
     * Handles APM Payments.
     * Corresponds to the async APM(data: APMInput): Promise<APMOutput> method.
     *
     * @param array $data The payment input data (APMInput).
     * @return array The payment output data (APMOutput).
     * @throws GuzzleException|\Exception
     */
    public function APM(array $data): array
    {
        $endpoint = $this->getEndpoint('APM');
        
        try {
            $response = $this->apiClient->post($endpoint, $data);
            return $response;
        } catch (GuzzleException $e) {
            $this->handleError($e, "APM payment");
        }
    }
    
// ----------------------------------
// --- Private Helper Methods ---
// ----------------------------------
    
}