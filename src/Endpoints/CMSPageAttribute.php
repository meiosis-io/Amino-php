<?php

namespace Meiosis\Endpoints;

use Meiosis\Endpoints\CRMObject;
use Meiosis\Endpoints\CRMObjectInterface;
use Meiosis\Exceptions\ObjectNotFoundException;
use Meiosis\Models\PageAttribute;

class CMSPageAttribute extends CRMObject implements CRMObjectInterface
{
    private $endpoint = 'cms/page-attributes/';
    protected $data = null;
    protected $pageType = null;
    public $attributes = [];

    public function __construct($apikey, $teamID, $api_url, $pageType)
    {
        parent::__construct($apikey, $teamID, $api_url);
        $this->pageType = $pageType;
        $this->endpoint .= $pageType . '/';
    }

    /**
     * Search for a matching attribute and return it
     * @param string $field
     * @param value $value
     * @return PageAttribute
     */
    public function search($field, $value)
    {
        $attributes = $this->all();

        foreach ($attributes as $attribute) {
            if ($attribute->{$field} == $value) {
                return $attribute;
            }
        }

        return null;
    }

    public function find($identifier)
    {
        $found = $this->search('id', $identifier);
        if (is_null($found)) {
            throw new ObjectNotFoundException('Not Found');
        }

        return $found;
    }

    public function all()
    {
        $attributes = [];
        $response = $this->apiClient->get(
            $this->endpoint,
            $this->payload()
        );

        foreach ($response as $attribute) {
            $attributes[] = new PageAttribute($attribute, $this);
        }

        return $attributes;
    }

    public function blueprint()
    {
        return new PageAttribute([], $this);
    }

    public function save($attribute)
    {
        if (is_null($attribute->id)) {
            $result = $this->create($attribute);
        }

        if (!is_null($attribute->id)) {
            $result = $this->update($attribute);
        }

        return $this->find($result->id);
    }

    /**
     * Creates an attribute if it doesn't
     * @param attribute $attribute
     * @return
     */
    protected function create($attribute)
    {
        return $this
            ->apiClient
            ->post($this->endpoint, $this->payload($attribute->extract()));
    }

    /**
     * Updates an existing attribute
     * @param attribute $attribute
     * @return type
     */
    protected function update($attribute)
    {
        $updateEndpoint = $this->endpoint . $attribute->id;
        return $this
            ->apiClient
            ->post($updateEndpoint, $this->payload($attribute->extract()));
    }

    public function delete($identifier)
    {
        return $this
            ->apiClient
            ->delete($this->endpoint . $identifier, $this->payload());
    }
}
