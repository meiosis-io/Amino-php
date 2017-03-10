<?php
namespace Meiosis;

use GuzzleHttp\Client;
use Meiosis\Constants\Api;
use GuzzleHttp\Exception\ClientException;

abstract class CRMObject
{
    protected $config = [];
    protected $data;

    public function __construct($apikey, $teamID, $api_url)
    {
        $this->config = [
            'api_token' => urlencode($apikey),
            'team'      => urlencode($teamID),
            'api_url'   => $api_url
        ];

        $this->data = (object) [];
    }

    public function exists()
    {
        // Return true or false based on populated...
        return ($this->id) ? true : false;
    }

    public function post($subPath = '', $data)
    {
        $client = new Client([
            'base_uri' => $this->config['api_url'] . $this->getEndpoint(),
            'http_errors' => false
        ]);

        $url = $subPath;

        $result = $client->request(
            'POST',
            $url,
            ['form_params' => $this->payload($data)]
        );

        if ($result->getStatusCode() != 200) {
            throw new \Exception('Cant post');
        }

        $this->data = json_decode($result->getBody());
        return $this;
    }

    public function get($subPath = '', $data = [])
    {
        $client = new Client([
            'base_uri' => $this->config['api_url'] . $this->getEndpoint(),
            'http_errors' => false
        ]);
        $url = $subPath;

        $result = $client->request(
            'GET',
            $url,
            ['query' => $this->payload($data)]
        );
        if ($result->getStatusCode() == '404') {
            return null;
        }

        $this->data = json_decode($result->getBody());

        return $this;
    }

    public function payload($data)
    {
        return array_merge([
            'api_token' => $this->config['api_token'],
            'team'      => $this->config['team']
        ], $data);
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if (property_exists($this->data, $name)) {
            return $this->data->{$name};
        }

        return null;
    }
}
