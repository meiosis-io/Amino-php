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

        if (array_key_exists('total', $data['details'])) {
            $payloadData['total'] = $data['details']['total'];
        }

        if (array_key_exists('items', $data['details'])) {
            $payloadData['items'] = $data['details']['items'];
        }

        if (array_key_exists('discount_type', $data['details'])) {
            $payloadData['discount_type'] = $data['details']['discount_type'];
        }

        if (array_key_exists('discount', $data['details'])) {
            $payloadData['discount'] = $data['details']['discount'];
        }

        if (array_key_exists('tax', $data['details'])) {
            $payloadData['tax'] = $data['details']['tax'];
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
}
