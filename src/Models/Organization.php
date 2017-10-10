<?php
namespace Meiosis\Models;

use Meiosis\Models\BaseModel;
use Meiosis\Models\Customer;

class Organization extends BaseModel
{
    // Set constant Fields
    protected static $native = [
        'id'      => null,
        'name'    => null,
        'address' => null,
        'city'    => null,
        'state'   => null,
        'zip'     => null,
        'created' => null,
        'updated' => null
    ];

    /**
     * Set the customers array to an array of customers
     * @param array $customers
     */
    public function set_customers($customers)
    {
        $customers = [];
        foreach ($customers as $customer) {
            $customers[] = new Customer($customer, $this->crmObject);
        }
        $this->data['customers'] = $customers;
    }
}
