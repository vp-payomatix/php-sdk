<?php

namespace PayAgency\Apis;

use PayAgency\lib\ApiClient;
use GuzzleHttp\Exception\GuzzleException;

/**
 * The PaymentLink class handles all payment link-related API calls.
 * It uses the custom ApiClient for automatic request encryption.
 */
class PaymentLink extends BaseApi
{

    /** @var array Test templates data for demo purposes */
    private $testTemplates = [];

    /**
     * Maps the environment to the corresponding API endpoint.
     *
     * @param string $type The payment link operation type.
     * @return string The correct endpoint URI.
     */
    private function getEndpoint(string $type): string
    {
        $endpoints = [
            'create' => [
                'test' => '/api/v1/payment-link',
                'live' => '/api/v1/payment-link',
            ],
            'templates' => [
                'test' => '/api/v1/payment-templates',
                'live' => '/api/v1/payment-templates',
            ],
        ];

        return $endpoints[$type][$this->env] ?? '';
    }

    /**
     * Get templates - getter method equivalent to TypeScript 'get templates()'.
     *
     * @return array The payment templates data.
     */
    public function getTemplates(): array
    {
        return $this->get_templates();
    }

    /**
     * Create a payment link.
     * Corresponds to the async create(data: PaymentLinkCreateInput): Promise<PaymentLinkCreateOutput> method.
     * Note: This method skips encryption as per the TypeScript implementation.
     *
     * @param array $data The payment link input data.
     * @return array The payment link output data.
     * @throws GuzzleException|\Exception
     */
    public function create(array $data): array
    {
        $endpoint = $this->getEndpoint('create');
        
        try {
            // Skip encryption for payment links as per TypeScript implementation
            $options = [
                'params' => ['Skip-Encryption' => 'true']
            ];
            
            $response = $this->apiClient->request('POST', $endpoint, [
                'json' => $data,
                'params' => $options['params']
            ]);
            
            return $response;
        } catch (GuzzleException $e) {
            $this->handleError($e, "Create payment link");
        }
    }

    /**
     * Fetch payment templates.
     * Corresponds to the private async getTemplates(): Promise<PaymentTemplatesOutput> method.
     *
     * @return array The payment templates output data.
     * @throws GuzzleException|\Exception
     */
    private function get_templates(): array
    {
        try {
            $endpoint = $this->getEndpoint('templates');
            
            // Return empty test data for test environment
            if ($this->env === 'test') {
                return [
                    'data' => $this->testTemplates
                ];
            }
            
            $response = $this->apiClient->get($endpoint);
            return $response;
        } catch (GuzzleException $e) {
            $this->handleError($e, "Fetch payment link templates");
        }
    }

}
