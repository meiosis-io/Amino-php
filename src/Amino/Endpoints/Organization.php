<?php
namespace Meiosis\Endpoints;

use Meiosis\CRMObject;
use Meiosis\Constants\Api;
use Meiosis\Exceptions\ObjectNotPopulatedException;
use Meiosis\Exceptions\ObjectNotFoundException;

class Organization extends CRMObject
{
    private $endpoint = 'organizations/';

    // Set constant Fields
    private $staticFields = [
        'id'            => 'string',
        'name'          => 'string',
        'address'        => 'string',
        'city'          => 'string',
        'state'         => 'string',
        'zip'           => 'string',
        'created'       => 'string',
        'updated'       => 'string'
    ];

    /**
     * Extract the private data
     * @return type
     */
    private function extract()
    {
        return $this->data;
    }

    public function find($identifier)
    {
        try {
            $this->data = $this->apiClient->get(
                $this->endpoint . $identifier,
                $this->payload()
            );
        } catch (ObjectNotFoundException $e) {
            // The object wasn't found, so don't do anything with it.
        }

        return $this;
    }

    public function create($data)
    {
        $safeData = $this->reconcilePayload($data);
        $created = $this->apiClient->post($this->endpoint, $safeData);

        return $this->find($created->id);
    }

    public function saveChanges()
    {
        $safeData = $this->reconcilePayload($this->data);
        $this->apiClient->post($this->endpoint . $this->id, $safeData);

        return $this;
    }

    public function delete($identifier)
    {
        // TODO: Implement
    }

    public function search($data)
    {
        try {
            $result = $this->apiClient->get(
                $this->endpoint,
                $this->payload($data)
            );

            if (! $result) {
                throw new ObjectNotFoundException('Nothing Found');
            }

            $this->data = $result[0];
        } catch (ObjectNotFoundException $e) {
            // The object wasn't found, so don't do anything with it.
        }

        return $this;
    }

    private function getAttributes()
    {

        return $this->apiClient->get('attributes/organization/', $this->payload());
    }

    private function reconcilePayload($data)
    {
        $valid = [];
        $return = [];
        foreach ($this->getAttributes() as $attribute) {
            $valid[] = $attribute->key;
        }

        foreach ($data as $key => $supplied) {
            if (array_key_exists($key, $this->staticFields)) {
                $return[$key] = $supplied;
            }
            if (in_array($key, $valid)) {
                $return[$key] = $supplied;
            }
        }

        return $return;
    }
}
