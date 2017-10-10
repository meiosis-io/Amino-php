<?php
namespace Meiosis\Endpoints;

use Meiosis\Constants\Api;
use Meiosis\Endpoints\CRMObject;
use Meiosis\Endpoints\CRMObjectInterface;
use Meiosis\Exceptions\ObjectNotFoundException;
use Meiosis\Exceptions\ObjectNotPopulatedException;
use Meiosis\Models\Customer;

/**
 * Class for working with the /customers endpoint
 */
class CRMCustomer extends CRMObject implements CRMObjectInterface
{
    protected $endpoint = 'customers/';
    protected static $returnType = Customer::class;

    /**
     * Returns a new, empty instance for populating
     * @return Customer
     */
    public function blueprint()
    {
        $attributes = Customer::getNativefields();
        $custom = $this->apiClient->get('attributes/customer/', $this->payload());

        foreach ($custom as $attribute) {
            $attributes[$attribute->key] = null;
        }

        return new Customer($attributes, $this);
    }

    /**
     * Track an Interaction on a customer
     * @param Customer $customer
     * @param String $source
     * @param String $description
     * @param integer $priority
     * @return Response
     */
    public function trackInteraction(Customer $customer, $source, $description, $priority = 5)
    {
        // Save the customer if they haven't been created
        if (is_null($customer->id)) {
            $customer->save();
        }

        // Build the payload
        $payload = $this->payload([
            'source'   => $source,
            'customer' => $customer->id,
            'description' => $description,
            'priority' => $priority
        ]);

        return $this->apiClient->post('track/', $payload);
    }
}
