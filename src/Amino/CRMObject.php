<?php

namespace Meiosis;

use GuzzleHttp\Client;
use Meiosis\Constants\Api;

abstract class CRMObject
{
    abstract protected function getEndpoint();

    public function save()
    {
        $data = [
            'api_token' => $this->apikey,
            'team'      => $this->teamID
        ];

        $client = new Client();
        $result = $client->request('POST', Api::API_BASEPATH . $this->getEndpoint(), $data);
        echo $res->getStatusCode();
        // "200"
        echo $res->getHeader('content-type');
        // 'application/json; charset=utf8'
        echo $res->getBody();
    }
}
