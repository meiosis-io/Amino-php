<?php
namespace Meiosis\Endpoints;

use Meiosis\Constants\Api;
use Meiosis\Endpoints\CRMObject;
use Meiosis\Endpoints\CRMObjectInterface;
use Meiosis\Exceptions\ObjectNotFoundException;
use Meiosis\Exceptions\ObjectNotPopulatedException;
use Meiosis\Models\Organization;

class CRMOrganization extends CRMObject implements CRMObjectInterface
{
    private $endpoint = 'organizations/';

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

        return new Organization($result, $this);
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
        foreach ($result as $organization) {
            $data[] = new Organization($organization);
        }

        return $data;
    }

    /**
     * Returns a new, empty instance for populating
     * @return Organization
     */
    public function blueprint()
    {
        $attributes = Organization::getNativefields();
        $custom = $this->apiClient->get('attributes/organization/', $this->payload());

        foreach ($custom as $attribute) {
            $attributes[$attribute->key] = null;
        }

        return new Organization($attributes, $this);
    }

    /**
     * Store an Organization
     * @param Organization $organization
     * @return type
     */
    public function save($organization)
    {
        if (is_null($organization->id)) {
            $result = $this->create($organization);
        }

        if (!is_null($organization->id)) {
            $result = $this->update($organization);
        }

        return $this->find($result->id);
    }

    /**
     * Creates an organization if they don't exist.
     * @param organization $organization
     * @return
     */
    protected function create($organization)
    {
        return $this
            ->apiClient
            ->post($this->endpoint, $this->payload($organization->extract()));
    }

    /**
     * Updates an existing organization
     * @param organization $organization
     * @return type
     */
    protected function update($organization)
    {
        $updateEndpoint = $this->endpoint . $organization->id;
        return $this
            ->apiClient
            ->post($updateEndpoint, $this->payload($organization->extract()));
    }

    /**
     * Deletes an Existing organization
     * @param Customer|String $identifier
     * @return type
     */
    public function delete($identifier)
    {
        if ($identifier instanceof Organization) {
            $deleteEndpoint = $this->endpoint . $identifier->id;
        }

        if (gettype($identifier) == 'string') {
            $deleteEndpoint = $this->endpoint . $identifier;
        }

        return $this
            ->apiClient
            ->delete($deleteEndpoint, $this->payload());
    }
}
