<?php
namespace Meiosis\Endpoints;

use Meiosis\Constants\Api;
use Meiosis\Endpoints\CRMObject;
use Meiosis\Endpoints\CRMObjectInterface;
use Meiosis\Exceptions\ObjectNotFoundException;
use Meiosis\Exceptions\ObjectNotPopulatedException;
use Meiosis\Models\Page;

class CMSPage extends CRMObject implements CRMObjectInterface
{
    protected $endpoint = 'cms/site/';
    protected $siteToken = '';

    public function find($identifier)
    {
        $page = $this->apiClient->get(
            $this->endpoint . $identifier,
            $this->payload()
        );

        return new Page($page);
    }

    public function blueprint()
    {
        return new Page([], $this);
    }

    public function getHierarchy($pageID = false)
    {
        $endpoint = "cms/hierarchy/{$this->siteToken}";
        if ($pageID) {
            $endpoint .= "/{$pageID}";
        }
        $result = null;

        $result = $this->apiClient->get($endpoint, $this->payload());

        $data = [];
        foreach ($result as $page) {
            $data[] = new Page((array) $page);
        }

        return $data;
    }

    public function bySlug($slug)
    {
        $result = $this->apiClient->get(
            $this->endpoint,
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
            $this->endpoint,
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
        $this->endpoint .= $token . '/page/';
        return $this;
    }
}
