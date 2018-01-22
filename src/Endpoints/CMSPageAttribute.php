<?php

namespace Meiosis\Endpoints;

use Meiosis\Amino;
use Meiosis\Endpoints\CRMObject;
use Meiosis\Endpoints\CRMObjectInterface;
use Meiosis\Exceptions\ObjectNotFoundException;
use Meiosis\Models\PageAttribute;

/**
 * Class for working with the /cms/page-attributes/{pageType} endpoint
 */
class CMSPageAttribute extends CRMObject implements CRMObjectInterface
{
    protected $endpoint = 'cms/page-attributes/';
    protected $data = null;
    protected $pageType = null;
    protected static $returnType = PageAttribute::class;

    public function __construct(Amino $amino, $pageType)
    {
        parent::__construct($amino);
        $this->pageType = $pageType;
        $this->endpoint .= $pageType . '/';
    }

    /**
     * Search for a matching attribute and return it
     * @param string $field
     * @param value $value
     * @return PageAttribute
     */
    public function search($searchArray)
    {
        $attributes = $this->all();

        foreach ($attributes as $attribute) {
            if ($this->testAttribute($searchArray, $attribute)) {
                return $attribute;
            }
        }

        return null;
    }

    /**
     * Apply the search Array to a given attribute
     * @param array $searchArray
     * @param attribute $attribute
     * @return boolean
     */
    private function testAttribute($searchArray, $attribute)
    {
        foreach ($searchArray as $field => $value) {
            if ($attribute->{$field} == $value) {
                return true;
            }
        }

        return false;
    }

    /**
     * Override the find method to use search
     * @param string $identifier
     * @return CMSPageAttribute
     */
    public function find($identifier)
    {
        $found = $this->search(['id' => $identifier]);
        if (is_null($found)) {
            throw new ObjectNotFoundException('Not Found');
        }

        return $found;
    }

    /**
     * Return an array of all PageAttributes
     * @return array of PageAttribute Objects
     */
    public function all()
    {
        $attributes = [];
        $response = $this->amino->client()->get(
            $this->endpoint,
            $this->payload()
        );

        foreach ($response as $attribute) {
            $attributes[] = new PageAttribute($attribute, $this);
        }

        return $attributes;
    }
}
