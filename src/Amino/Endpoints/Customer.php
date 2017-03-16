<?php
namespace Meiosis\Endpoints;

use Meiosis\CRMObject;
use Meiosis\Constants\Api;
use Meiosis\Exceptions\ObjectNotPopulatedException;
use Meiosis\Exceptions\ObjectNotFoundException;

class Customer extends CRMObject
{
    private $endpoint = 'customers/';

    // Set constant Fields
    private $staticFields = [
        'id'            => 'string',
        'first'         => 'string',
        'middle'        => 'string',
        'last'          => 'string',
        'email'         => 'string',
        'organization'  => 'string',
        'created'       => 'string',
        'updated'       => 'string',
        'user_id'       => 'string',
    ];

    /**
     * Extract the private data
     * @return type
     */
    private function extract()
    {
        return $this->data;
    }

    public function track($source, $description)
    {
        if (! $this->exists()) {
            throw new ObjectNotPopulatedException('Need to get a customer first');
        }

        $payload = $this->payload([
            'source'   => $source,
            'customer' => $this->id,
            'description' => $description
        ]);

        return $this->apiClient->post('track/', $payload);
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
        $created = $this->apiClient->post($this->endpoint, $this->payload($safeData));

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

    private function getAttributes()
    {

        return $this->apiClient->get('attributes/customer/', $this->payload());
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
