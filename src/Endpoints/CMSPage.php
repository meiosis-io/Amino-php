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
    private $endpoint = 'cms/site/';
    private $siteToken = '';

    public function find($identifier)
    {
        $page = $this->apiClient->get(
            $this->endpoint . $identifier,
            $this->payload()
        );

        return new Page($page);
    }

    public function save($page)
    {
        if (is_null($page->id)) {
            $result = $this->create($page);
        }

        if (!is_null($page->id)) {
            $result = $this->update($page);
        }

        return $this->find($result->id);
    }

    /**
     * Creates a site if it doesn't
     * @param Site $site
     * @return
     */
    protected function create($page)
    {
        return $this
            ->apiClient
            ->post($this->endpoint, $this->payload($page->extract()));
    }

    /**
     * Updates an existing site
     * @param Site $site
     * @return type
     */
    protected function update($page)
    {
        $updateEndpoint = $this->endpoint . $page->id;
        return $this
            ->apiClient
            ->post($updateEndpoint, $this->payload($page->extract()));
    }

    public function blueprint()
    {
        return new Page([], $this);
    }

    /**
     * Deletes an Existing Page
     * @param Page|String $identifier
     * @return type
     */
    public function delete($identifier)
    {
        if ($identifier instanceof Page) {
            $deleteEndpoint = $this->endpoint . $identifier->id;
        }

        if (gettype($identifier) == 'string') {
            $deleteEndpoint = $this->endpoint . $identifier;
        }

        return $this
            ->apiClient
            ->delete($deleteEndpoint, $this->payload());
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
