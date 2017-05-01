<?php
namespace Meiosis\Endpoints;

use Meiosis\CRMObject;
use Meiosis\Constants\Api;
use Meiosis\Exceptions\ObjectNotPopulatedException;
use Meiosis\Exceptions\ObjectNotFoundException;

class Transaction extends CRMObject
{
    private $endpoint = 'transactions/';

    // Set constant Fields
    private $staticFields = [
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
        $payloadData['customer'] = $data['customer']->id;

        if (array_key_exists('total', $data)) {
            $payloadData['total'] = $data['total'];
        }

        if (array_key_exists('items', $data)) {
            $payloadData['items'] = $data['items'];
        }

        $created = $this->apiClient
            ->post($this->endpoint, $this->payload($payloadData));

        return $this->find($created->id);
    }

    public function saveChanges()
    {
        // TODO: Implement
    }

    public function delete($identifier)
    {
        // TODO: Implement
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
