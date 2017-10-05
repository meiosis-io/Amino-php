<?php
namespace Meiosis\Endpoints;

use Meiosis\Constants\Api;
use Meiosis\Endpoints\CRMObject;
use Meiosis\Endpoints\CRMObjectInterface;
use Meiosis\Exceptions\ObjectNotFoundException;
use Meiosis\Exceptions\ObjectNotPopulatedException;
use Meiosis\Models\Customer;

class CRMCustomer extends CRMObject implements CRMObjectInterface
{
    private $endpoint = 'customers/';

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

        return new Customer($result, $this);
    }

    /**
     * Search
     * @return type
     */

    public function search($searchArray)
    {
        $result = $this->apiClient->get(
            $this->endpoint,
            $this->payload($searchArray)
        );

        $data = [];
        foreach ($result as $customer) {
            $data[] = new Customer($customer, $this);
        }

        return $data;
    }

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
     * Store a customer
     * @param Customer $customer
     * @return type
     */
    public function save($customer)
    {
        if (is_null($customer->id)) {
            $result = $this->create($customer);
        }

        if (!is_null($customer->id)) {
            $result = $this->update($customer);
        }

        return $this->find($result->id);
    }

    /**
     * Creates a customer if they don't exist.
     * @param Customer $customer
     * @return
     */
    protected function create($customer)
    {
        return $this
            ->apiClient
            ->post($this->endpoint, $this->payload($customer->extract()));
    }

    /**
     * Updates an existing customer
     * @param Customer $customer
     * @return type
     */
    protected function update($customer)
    {
        $updateEndpoint = $this->endpoint . $customer->id;
        return $this
            ->apiClient
            ->post($updateEndpoint, $this->payload($customer->extract()));
    }

    /**
     * Deletes an Existing Customer
     * @param Customer|String $identifier
     * @return type
     */
    public function delete($identifier)
    {
        if ($identifier instanceof Customer) {
            $deleteEndpoint = $this->endpoint . $identifier->id;
        }

        if (gettype($identifier) == 'string') {
            $deleteEndpoint = $this->endpoint . $identifier;
        }

        return $this
            ->apiClient
            ->delete($deleteEndpoint, $this->payload());
    }

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
