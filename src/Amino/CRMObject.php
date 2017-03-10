<?php
namespace Meiosis;

use GuzzleHttp\Client;
use Meiosis\Constants\Api;

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

    public function get($data = [], $subPath = '')
    {
        $client = new Client();
        $url = $this->config['api_url'] . $this->getEndpoint() . $subPath;

        $result = $client->request(
            'GET',
            $url,
            $this->payload($data)
        );

        dd($result);
    }

    protected function payload($data)
    {
        return array_merge([
            'api_token' => $this->config['apikey'],
            'team'      => $this->config['teamID']
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
