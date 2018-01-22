<?php
namespace Meiosis\ApiClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Meiosis\Amino;
use Meiosis\Constants\Api;
use Meiosis\Exceptions\InvalidEndpointException;
use Meiosis\Exceptions\ObjectNotFoundException;
use Meiosis\Exceptions\ObjectValidationFailedException;
use Meiosis\Exceptions\UnknownApiException;

class ApiClient
{

    private $client;
    private $amino;

    public function __construct(Amino $amino)
    {
        $this->amino = $amino;

        $this->client = new Client([
            'base_uri' => $this->amino->getApiBase(),
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->amino->getApiToken(),
                'X-TEAM' => $this->amino->getTeamToken()
            ]
        ]);
    }

    /**
     * Issue a GET request
     * @param string $endpoint - API Endpoint
     * @param array $queryParams - Extra Query Params
     * @return json - Response
     */
    public function get($endpoint, $queryParams)
    {
        $result = $this->client->request('GET', $endpoint, ['query' => $queryParams]);

        return $this->checkAndReturnResponse($result);
    }

    /**
     * Issue a POST request
     * @param string $endpoint - API Endpoint
     * @param array $data - Post Parameters
     * @return json - Response
     */
    public function post($endpoint, $data)
    {
        $result = $this->client->request('POST', $endpoint, ['form_params' => $data]);

        return $this->checkAndReturnResponse($result);
    }

    /**
     * Issue a DELETE request
     * @param string $endpoint - API Endpoint
     * @param array $queryParams - Extra Query Parameters
     * @return json - Response
     */
    public function delete($endpoint, $queryParams)
    {
        $result = $this->client->request('DELETE', $endpoint, ['query' => $queryParams]);

        return $this->checkAndReturnResponse($result);
    }

    /**
     * Verify a raw request and throw exceptions if errors found
     * @param GuzzleHttp\Psr7\Response $response - Guzzle response object
     * @return json - Response
     */
    private function checkAndReturnResponse($response)
    {
        $status = $response->getStatusCode();
        if ($status == 200) {
            return json_decode($response->getBody());
        }

        if ($status == 404) {
            throw new ObjectNotFoundException;
        }

        if ($status == 422) {
            throw new ObjectValidationFailedException($response->getBody());
        }

        throw new UnknownApiException('Returned Status: ' . $status);
    }
}
