<?php
namespace Meiosis\Models;

use Meiosis\Endpoints\CRMTransaction;
use Meiosis\Exceptions\UseOtherMethodException;
use Meiosis\Models\BaseModel;
use Meiosis\Models\Customer;
use Meiosis\Models\TransactionItem;

class Transaction extends BaseModel
{
    protected static $native = [
        'customer'      => null, // Customer ID
        'total'         => null, // Transaction Total (Optional)
        'items'         => [], // Array of Meiosis\Models\TransactionItem Objects
        'discount_type' => 'none', // Can be "fixed", "percent", "or none"
        'discount'      => null, // Used with discount_type
        'tax'           => 0, // Tax Percentage (As decimal)
    ];

    /**
     * Set the customer ID
     * @param Customer|string $customer
     */
    public function set_customer($customer)
    {
        if ($customer instanceof Customer) {
            $this->data['customer'] = $customer->id;
        }

        if (gettype($customer) == 'string') {
            $this->data['customer'] = $customer;
        }
    }

    /**
     * Add an Array of items
     * @param array $itemArray
     */
    public function set_items($itemArray)
    {
        $this->clearItems();
        foreach ($itemArray as $item) {
            $this->addItem(new TransactionItem($item));
        }
    }

    /**
     * Add a single item
     * @param TransactionItem $item
     */
    public function addItem(TransactionItem $item)
    {
        // Push the item on to the arrays
        array_push($this->data['items'], $item->extract());

        $this->updateTotal();
    }

    /**
     * Re calculate the total
     */
    private function updateTotal()
    {
        $total = 0;

        foreach ($this->data['items'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $this->data['total'] = $total;
    }

    /**
     * Clears out the items for a given transaction
     */
    public function clearItems()
    {
        $this->data['items'] = [];
    }

    /**
     * Return an array of TransactionItem Objects
     */
    public function get_items()
    {
        $items = [];
        foreach ($this->data['items'] as $item) {
            $items[] = new TransactionItem($item);
        }

        return $items;
    }

    /**
     * Create a negative version of this transaction
     */
    public function void()
    {
        $transaction = $this->extract();
        $transaction['id'] = null;

        $transaction['total'] = $transaction['total'] * -1;
        if (is_null($transaction['discount_type'])) {
            $transaction['discount_type'] = 'none';
        }

        foreach ($transaction['items'] as &$item) {
            $item['quantity'] = $item['quantity'] * -1;
            $item['description'] = '(--VOID--) ' . $item['description'];
        }

        $newTransaction = new Transaction($transaction, $this->crmObject);
        $newTransaction->save();

        $this->populate($newTransaction->extract());
    }
}
