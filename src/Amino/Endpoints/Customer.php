<?php
namespace Meiosis\Endpoints;

use Meiosis\CRMObject;
use Meiosis\Constants\Api;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Customer extends CRMObject
{
    // Set constant Fields
    private $staticFields = [
        'id' => 'string',
        'first' => 'string',
        'middle' => 'string',
        'last' => 'string',
        'email' => 'string',
        'organization' => 'string',
        'created' => 'string',
        'updated' => 'string',
        'user_id' => 'string',
    ];

    public function availableFields()
    {

    }

    public function getEndpoint()
    {
        return 'customers/';
    }

    // Return the valid object params
    private function toData()
    {
        return $this->data;
    }

    public function track($source, $description)
    {
        if (! $this->exists()) {
            throw new Exception('Need to get a customer first');
        }

        $client = new Client([
            'base_uri' => $this->config['api_url'] . 'track/',
            'http_errors' => false
        ]);

        $result = $client->request(
            'POST',
            '',
            [
                'form_params' => $this->payload([
                    'source' => $source,
                    'customer' => $this->id,
                    'description' => $description,
                ])
            ]
        );

        if ($result->getStatusCode() != 200) {
            throw new \Exception('Cant post');
        }

        return json_decode($result->getBody());
    }

    public function find($identifier)
    {
        $this->get($identifier, []);

        return $this;
    }

    public function create($data)
    {
        $safeData = $this->reconcilePayload($data);
        $this->post('', $safeData);

        return $this;
    }

    private function getAttributes()
    {
        $client = new Client([
            'base_uri' => $this->config['api_url'] . 'attributes/customer/',
            'http_errors' => false
        ]);

        $result = $client->request(
            'GET',
            '',
            ['query' => $this->payload([])]
        );

        return json_decode($result->getBody());
    }

    private function reconcilePayload($data)
    {
        $valid = [];
        $return = [];
        foreach ($this->getAttributes() as $attribute) {
            $valid[] = $attribute->key;
        }

        foreach ($data as $key => $supplied) {
            if (in_array($key, $this->staticFields)) {
                $return[$key] = $supplied;
            }
            if (in_array($key, $valid)) {
                $return[$key] = $supplied;
            }
        }

        return $return;
    }
}
