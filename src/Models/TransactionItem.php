<?php
namespace Meiosis\Models;

class TransactionItem extends BaseModel
{
    // Set constant Fields
    protected static $native = [
        'price'         => null, // Required
        'quantity'      => 1, // Optional, Defaults to 1
        'id'       => null, // Optional
        'description'   => null, // Optional
    ];

    /**
     * Set the price
     * @param string|float|int $price
     */
    public function set_price($price)
    {
        $this->data['price'] = floatval($price);
    }

    /**
     * Set the quantitiy
     * @param string|float|int $quantity
     */
    public function set_quantity($quantity)
    {
        $this->data['quantity'] = floatval($quantity);
    }

    /**
     * Set the item ID
     * @param string $item
     */
    public function set_item_id($item)
    {
        $this->data['id'] = $item;
    }
}
