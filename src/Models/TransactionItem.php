<?php
namespace Meiosis\Models;

class TransactionItem extends BaseModel
{
    // Set constant Fields
    protected static $native = [
        'price'         => null, // Required
        'quantity'      => 1, // Optional, Defaults to 1
        'item_id'       => null, // Optional
        'description'   => null, // Optional
    ];

    public function setPrice($price)
    {
        $this->data['price'] = floatval($price);
    }

    public function setQuantity($quantity)
    {
        $this->data['quantity'] = floatval($quantity);
    }
}
