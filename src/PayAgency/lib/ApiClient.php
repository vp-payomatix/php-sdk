<?php

namespace PayAgency\lib;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use RuntimeException;

/**
 * HTTP client for PayAgency API with automatic encryption
 */
class ApiClient
{
    private GuzzleClient $httpClient;
    private string $encryptionKey;
    private string $env;

    public function __construct(array $options)
    {
        if (!isset($options['encryptionKey'], $options['secretKey'])) {
            throw new RuntimeException('Both encryptionKey and secretKey are required.');
        }

        $this->encryptionKey = $options['encryptionKey'];
        $this->env = str_starts_with($options['secretKey'], 'PA_LIVE_') ? 'live' : 'test';

        $baseUrl = $options['baseUrl'] ?? 'https://backend.pay.agency';
        $baseUrl = rtrim($baseUrl, '/'); // Ensure no trailing slash
        if (!str_starts_with($baseUrl, 'https://')) {
            $baseUrl = "https://$baseUrl"; // Ensure it starts with https
        }

        $this->httpClient = new GuzzleClient([
            'base_uri' => $baseUrl,
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $options['secretKey'],
            ],
            'timeout' => 15.0,
        ]);
    }

    public function request(string $method, string $uri, array $options = [])
    {
        try {
            // Check if encryption should be skipped (support both formats)
            $skipEncryption = ($options['params']['Skip-Encryption'] ?? 'false') === 'true' || 
                            ($options['skip_encryption'] ?? false) === true;

            if (isset($options['json']) && !$skipEncryption) {
                // Encrypt the request payload
                $options['json'] = [
                    'payload' => $this->encryptData(json_encode($options['json'], JSON_THROW_ON_ERROR)),
                ];
            }

            // If skip_encryption was used, add it as a query parameter for the API
            if ($options['skip_encryption'] ?? false) {
                $options['query']['Skip-Encryption'] = 'true';
                unset($options['skip_encryption']); // Remove from options to avoid conflicts
            }

            $response = $this->httpClient->request($method, $uri, $options);
            return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (RequestException $e) {
            throw new RuntimeException('Request failed: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function post(string $uri, array $data, array $options = [])
    {
        $options['json'] = $data;
        return $this->request('POST', $uri, $options);
    }

    public function get(string $uri, array $options = [])
    {
        return $this->request('GET', $uri, $options);
    }

    public function getEnvironment(): string
    {
        return $this->env;
    }

    /**
     * Encrypts data using AES-256-CBC encryption.
     * Equivalent to the TypeScript version:
     * function encryptData(data: string, key: string): string {
     *   const iv = randomBytes(16);
     *   const cipher = createCipheriv("aes-256-cbc", Buffer.from(key, "utf-8"), iv);
     *   let encrypted = cipher.update(data, "utf-8");
     *   encrypted = Buffer.concat([encrypted, cipher.final()]);
     *   return iv.toString("hex") + ":" + encrypted.toString("hex");
     * }
     */
    private function encryptData(string $data): string
    { 
        $iv = random_bytes(16);
        $cipher = 'aes-256-cbc';
        
        // Use the encryption key directly as UTF-8 bytes, pad/truncate to 32 bytes for AES-256
        $key = substr(str_pad($this->encryptionKey, 32, "\0"), 0, 32);

        $encrypted = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        if ($encrypted === false) {
            throw new RuntimeException('Encryption failed.');
        }

        return bin2hex($iv) . ':' . bin2hex($encrypted);
    }
}
