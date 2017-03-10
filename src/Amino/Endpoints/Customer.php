<?php
namespace Meiosis\Endpoints;

use Meiosis\CRMObject;

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
        if ($this->data['id']) {
            throw new Exception('Need to get a customer first');
        }

        $client = new Client([
            'base_uri' => $this->config['api_url'] . 'track/',
            'http_errors' => false
        ]);

        $url = $subPath;

        $result = $client->request(
            'POST',
            $url,
            [
                'form_params' => $this->payload([
                    'source' => $source,
                    'customer' => $this->data['id'],
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
        return $this->toData();
    }

    public function create($data)
    {
        $this->post('', $data);
        return $this->toData();
    }
}
