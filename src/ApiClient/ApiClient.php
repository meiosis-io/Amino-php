<?php
namespace Meiosis\ApiClient;

use GuzzleHttp\Client;
use Meiosis\Constants\Api;
use GuzzleHttp\Exception\ClientException;

use Meiosis\Exceptions\ObjectNotFoundException;
use Meiosis\Exceptions\InvalidEndpointException;
use Meiosis\Exceptions\ObjectValidationFailedException;
use Meiosis\Exceptions\UnknownApiException;

class ApiClient
{

    private $client;

    public function __construct($url)
    {
        $this->client = new Client([
            'base_uri' => $url,
            'http_errors' => false
        ]);
    }

    public function get($endpoint, $queryParams)
    {
        $result = $this->client->request('GET', $endpoint, ['query' => $queryParams]);
        return $this->checkAndReturnResponse($result);
    }

    public function post($endpoint, $data)
    {
        $result = $this->client->request('POST', $endpoint, ['form_params' => $data]);

        return $this->checkAndReturnResponse($result);
    }

    public function delete($endpoint, $queryParams)
    {
        $result = $this->client->request('DELETE', $endpoint, ['query' => $queryParams]);

        return $this->checkAndReturnResponse($result);
    }

    private function checkAndReturnResponse($response)
    {
        $status = $response->getStatusCode();

        if ($status == 404) {
            throw new ObjectNotFoundException;
        }

        if ($status == 422) {
            throw new ObjectValidationFailedException($response->getBody());
        }

        if ($status != 200) {
            throw new UnknownApiException('Returned Status: ' . $status);
        }

        return json_decode($response->getBody());
    }
}
