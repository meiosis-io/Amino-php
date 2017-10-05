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

    public function set_price($price)
    {
        $this->data['price'] = floatval($price);
    }

    public function set_quantity($quantity)
    {
        $this->data['quantity'] = floatval($quantity);
    }

    public function set_item_id($item)
    {
        $this->data['id'] = $item;
    }
}
