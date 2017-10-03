<?php

namespace Meiosis\Endpoints;

use Meiosis\Endpoints\CRMObject;
use Meiosis\Models\PageAttribute;

class CMSPageAttribute extends CRMObject
{
    private $endpoint = 'cms/page-attributes/';
    protected $data = null;
    protected $pageType = null;
    public $attributes = [];

    public function __construct($apikey, $teamID, $api_url, $pageType)
    {
        parent::__construct($apikey, $teamID, $api_url);
        $this->pageType = $pageType;
        $this->load($pageType);
    }

    /**
     * Search for a matching attribute and return it
     * @param string $field
     * @param value $value
     * @return PageAttribute
     */
    public function search($field, $value)
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->{$field} == $value) {
                return $attribute;
            }
        }

        return null;
    }

    public function load($pageType)
    {
        $response = $this->apiClient->get(
            $this->endpoint . $pageType,
            $this->payload()
        );

        foreach ($response as $attribute) {
            $this->attributes[] = new PageAttribute($attribute);
        }
        return $this;
    }

    public function find($identifier)
    {
        throw new \Exception('Not Implemented - Use ->search($key, $value) instead');
    }

    public function create($data)
    {
        throw new \Exception('Not Implemented');
    }

    public function saveChanges()
    {
        throw new \Exception('Not Implemented');
    }

    public function delete($identifier)
    {
        throw new \Exception('Not Implemented');
    }
}
