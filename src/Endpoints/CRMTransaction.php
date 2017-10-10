<?php
namespace Meiosis\Endpoints;

use Meiosis\Constants\Api;
use Meiosis\Endpoints\CRMObject;
use Meiosis\Endpoints\CRMObjectInterface;
use Meiosis\Exceptions\InvalidEndpointException;
use Meiosis\Exceptions\ObjectNotFoundException;
use Meiosis\Exceptions\ObjectNotPopulatedException;
use Meiosis\Models\Transaction;

class CRMTransaction extends CRMObject implements CRMObjectInterface
{
    protected $endpoint = 'transactions/';

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
        throw new InvalidEndpointException('Search not available for transactions');
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
        throw new InvalidEndpointException('Existing transactions can not be updated. You should destroy and re-issue the transaction.');
    }
}
