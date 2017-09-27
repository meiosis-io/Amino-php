<?php
namespace Meiosis\Endpoints;

use Meiosis\CRMObject;
use Meiosis\Constants\Api;
use Meiosis\Exceptions\ObjectNotPopulatedException;
use Meiosis\Exceptions\ObjectNotFoundException;

class CMSPage extends CRMObject
{
    private $endpoint = 'cms/site/';
    private $siteToken = '';
    protected $data = null;

    public function find($identifier)
    {
        return $this->byId($identifier);
    }

    public function create($data)
    {

    }

    public function saveChanges()
    {

    }

    public function delete($identifier)
    {

    }

    public function byId($pageID)
    {
        $this->data = $this->apiClient->get(
            $this->endpoint . $this->siteToken . '/page/' . $pageID,
            $this->payload()
        );

        return $this;
    }

    public function getHierarchy($pageID = false)
    {
        $endpoint = "cms/hierarchy/{$this->siteToken}";
        if ($pageID) {
            $endpoint .= "/{$pageID}";
        }
        $result = null;

        $result = $this->apiClient->get(
            $endpoint,
            $this->payload()
        );

        return $result;
    }

    public function bySlug($slug)
    {
        $this->data = $this->apiClient->get(
            $this->endpoint . $this->siteToken . '/page/',
            $this->payload([
                'slug' => $slug
            ])
        );

        return $this;
    }

    /**
     * Search By Attribute
     */
    public function byAttributes($attributes)
    {
        $this->data = $this->apiClient->get(
            $this->endpoint . $this->siteToken . '/page/',
            $this->payload($attributes)
        );

        return $this;
    }

     /**
     * Extract the private data
     * @return type
     */
    public function extract()
    {
        return $this->data;
    }

    public function setSiteToken($token)
    {
        $this->siteToken = $token;
        return $this;
    }
}
