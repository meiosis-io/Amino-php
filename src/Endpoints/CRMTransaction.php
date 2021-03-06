<?php
namespace Meiosis\Endpoints;

use Meiosis\Constants\Api;
use Meiosis\Endpoints\CRMObject;
use Meiosis\Endpoints\CRMObjectInterface;
use Meiosis\Exceptions\InvalidEndpointException;
use Meiosis\Exceptions\ObjectNotFoundException;
use Meiosis\Exceptions\ObjectNotPopulatedException;
use Meiosis\Exceptions\UseOtherMethodException;
use Meiosis\Models\Transaction;

/**
 * Class for working with the /transactions endpoint
 */
class CRMTransaction extends CRMObject implements CRMObjectInterface
{
    protected $endpoint = 'transactions/';
    protected static $returnType = Transaction::class;

    /**
     * Search Method - Not Available for transactions, so it will throw an exception
     * @param array $searchArray
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
        throw new UseOtherMethodException('Existing transactions can not be updated. You should destroy and re-issue the transaction.');
    }
}
