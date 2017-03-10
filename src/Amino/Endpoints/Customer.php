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
    private function parse($object)
    {
        return $object;
    }

    public function find($identifier)
    {
        $response = $this->get($identifier, []);
        return $this->parse($response);
    }

    public function create($data)
    {
        $response = $this->post('', $data);
        return $this->find($response->id);
    }
}
