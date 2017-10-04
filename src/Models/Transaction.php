<?php
namespace Meiosis\Models;

use Meiosis\Models\BaseModel;
use Meiosis\Models\Customer;
use Meiosis\Models\TransactionItem;
use Meiosis\Exceptions\UseOtherMethodException;

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

    public function setCustomer($customer)
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
    public function setItems($itemArray)
    {
        $this->data['items'] = [];
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

    // Provide back the transaction items
    public function getItems()
    {
        $items = [];
        foreach ($this->data['items'] as $item) {
            $items[] = new TransactionItem($item);
        }

        return $items;
    }
}
