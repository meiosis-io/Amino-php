<?php
namespace Meiosis\Endpoints;

use Meiosis\Endpoints\CRMObject;
use Meiosis\Constants\Api;
use Meiosis\Exceptions\ObjectNotFoundException;
use Meiosis\Exceptions\ObjectNotPopulatedException;
use Meiosis\Models\Page;

class CMSPage extends CRMObject
{
    private $endpoint = 'cms/site/';
    private $siteToken = '';

    public function find($identifier)
    {
        return $this->byId($identifier);
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

    public function byId($pageID)
    {
        $page = $this->apiClient->get(
            $this->endpoint . $this->siteToken . '/page/' . $pageID,
            $this->payload()
        );


        return new Page($page);
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

        $data = [];
        foreach ($result as $page) {
            $data[] = new Page((array) $page);
        }

        return $data;
    }

    public function bySlug($slug)
    {
        $result = $this->apiClient->get(
            $this->endpoint . $this->siteToken . '/page/',
            $this->payload([
                'slug' => $slug
            ])
        );

        $data = [];
        foreach ($result as $page) {
            $data[] = new Page($page);
        }

        return $data;
    }

    /**
     * Search By Attribute
     */
    public function byAttributes($attributes)
    {
        $result = $this->apiClient->get(
            $this->endpoint . $this->siteToken . '/page/',
            $this->payload($attributes)
        );

        $data = [];
        foreach ($result as $page) {
            $data[] = new Page($page);
        }

        return $data;
    }

    public function setSiteToken($token)
    {
        $this->siteToken = $token;
        return $this;
    }
}
