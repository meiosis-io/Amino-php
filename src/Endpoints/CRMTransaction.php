<?php
namespace Meiosis\Endpoints;

use Meiosis\Endpoints\CRMObject;
use Meiosis\Models\Transaction;
use Meiosis\Constants\Api;
use Meiosis\Exceptions\ObjectNotPopulatedException;
use Meiosis\Exceptions\ObjectNotFoundException;

class CRMTransaction extends CRMObject
{
    private $endpoint = 'transactions/';

    /**
     * Given an identifier for our object, find and return exactly one
     * @param string $identifier
     * @return type
     */
    public function find($identifier)
    {
        $result = $this->apiClient->get(
            $this->endpoint . $identifier,
            $this->payload()
        );

        return new Transaction($result, $this);
    }

    /**
     * Search
     * @return type
     */

    public function search($searchArray)
    {
        $result = $this->apiClient->get(
            $this->endpoint,
            $this->payload($searchArray)
        );

        $data = [];
        foreach ($result as $transaction) {
            $data[] = new Transaction($transaction, $this);
        }

        return $data;
    }

    /**
     * Returns a new, empty instance for populating
     * @return Customer
     */
    public function blueprint()
    {
        return new Transaction([], $this);
    }

    /**
     * Store a Transaction
     * @param Transaction $transaction
     * @return type
     */
    public function save($transaction)
    {
        if (is_null($transaction->id)) {
            $result = $this->create($transaction);
        }

        if (!is_null($transaction->id)) {
            $result = $this->update($transaction);
        }

        return $this->find($result->id);
    }

    /**
     * Creates a Transaction if it doesn't exist.
     * @param Transaction $transaction
     * @return
     */
    protected function create($transaction)
    {
        return $this
            ->apiClient
            ->post($this->endpoint, $this->payload($transaction->extract()));
    }

    /**
     * Updates an existing Transaction
     * @param Transaction $transaction
     * @return type
     */
    protected function update($transaction)
    {
        $updateEndpoint = $this->endpoint . $transaction->id;
        return $this
            ->apiClient
            ->post($updateEndpoint, $this->payload($transaction->extract()));
    }

    /**
     * Deletes an Existing Transaction
     * @param Transaction|String $identifier
     * @return type
     */
    public function delete($identifier)
    {
        if ($identifier instanceof Transaction) {
            $deleteEndpoint = $this->endpoint . $identifier->id;
        }

        if (gettype($identifier) == 'string') {
            $deleteEndpoint = $this->endpoint . $identifier;
        }

        return $this
            ->apiClient
            ->delete($deleteEndpoint, $this->payload());
    }
}