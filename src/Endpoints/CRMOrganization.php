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
    protected $endpoint = 'organizations/';

    protected static $returnType = Organization::class;

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
}
