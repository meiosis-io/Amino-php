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

    public function find($identifier)
    {
        $this->get($identifier, []);
    }
}