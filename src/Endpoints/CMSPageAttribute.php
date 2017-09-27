<?php

namespace Meiosis\Endpoints;

use Meiosis\CRMObject;
use Meiosis\Models\PageAttribute;

class CMSPageAttribute extends CRMObject
{
    private $endpoint = 'cms/page-attributes/';
    protected $data = null;

    public function find($identifier)
    {
        $attribute = $this->apiClient->get(
            $this->endpoint . $identifier,
            $this->payload()
        );

        return new PageAttribute($attribute);
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
