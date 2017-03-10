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
    }

    public function post($data)
    {
        $client = new Client();
        $result = $client->request('POST', Api::API_BASEPATH . $this->getEndpoint(), $data);
        echo $res->getStatusCode();
        // "200"
        echo $res->getHeader('content-type');
        // 'application/json; charset=utf8'
        echo $res->getBody();
    }

    public function get($subPath = '', $data = [])
    {
        // dd($this->config['api_url'] . $this->getEndpoint());
        $client = new Client(['base_uri' => $this->config['api_url'] . $this->getEndpoint()]);
        $url = $subPath;

        try {
            $result = $client->request(
                'GET',
                $url,
                ['query' => $this->payload($data)]
            );
        } catch (ClientException $e) {
            if ($e->getStatusCode() == '404') {
                return null;
            }
        }

        return $result->getBody()->getContents();
    }

    protected function payload($data)
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
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return null;
    }
}
