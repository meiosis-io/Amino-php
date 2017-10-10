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

    protected static $returnType = Transaction::class;

    /**
     * Search
     * @return type
     */
    public function search($searchArray)
    {
        throw new InvalidEndpointException('Search not available for transactions');
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
