<?php

namespace PayAgency\Apis;

use PayAgency\lib\ApiClient;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Base class for all API modules providing common functionality.
 */
abstract class BaseApi
{
    /** @var ApiClient */
    protected $apiClient;

    /** @var string "test" or "live" */
    protected $env;

    /**
     * @param ApiClient $apiClient An instance of the custom ApiClient.
     * @param string $env The environment ("test" or "live").
     */
    public function __construct(ApiClient $apiClient, string $env = "test")
    {
        $this->apiClient = $apiClient;
        $this->env = $env;
    }

    /**
     * Common error handler to log and re-throw exceptions.
     * Mimics the console.error logic in the TypeScript code.
     *
     * @param GuzzleException $e The exception caught.
     * @param string $operation A descriptive name for the operation.
     * @throws GuzzleException
     */
    protected function handleError(GuzzleException $e, string $operation): void
    {
        $errorMessage = $e->getMessage();

        if ($e instanceof \GuzzleHttp\Exception\RequestException && $e->hasResponse()) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            
            try {
                $responseData = $response->getBody()->getContents();
                error_log("Error in {$operation}: Status {$statusCode}, Body: {$responseData}");
                $response->getBody()->rewind(); 
            } catch (\Exception $bodyReadE) {
                error_log("Error in {$operation}: Could not read response body. Message: {$e->getMessage()}");
            }
        } else {
            error_log("Error in {$operation}: {$errorMessage}");
        }

        throw $e;
    }
}
