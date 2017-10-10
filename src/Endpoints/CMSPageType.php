<?php
namespace Meiosis\Endpoints;

use Meiosis\Constants\Api;
use Meiosis\Endpoints\CRMObject;
use Meiosis\Endpoints\CRMObjectInterface;
use Meiosis\Exceptions\ObjectNotFoundException;
use Meiosis\Exceptions\ObjectNotPopulatedException;
use Meiosis\Models\PageType;

class CMSPageType extends CRMObject implements CRMObjectInterface
{
    protected $endpoint = 'cms/page-type/';
    protected $siteToken = '';

    public function find($identifier)
    {
        $pageType = $this->apiClient->get(
            $this->endpoint . $identifier,
            $this->payload()
        );

        return new PageType($pageType);
    }

    /**
     * Save a page type
     * @param PageType $pageType
     * @return PageType
     */
    public function save($pageType)
    {
        if (is_null($pageType->id)) {
            $result = $this->create($pageType);
        }

        if (!is_null($pageType->id)) {
            $result = $this->update($pageType);
        }

        return $this->find($result->id);
    }

    /**
     * Creates a pagetype if it doesn't Exist
     * @param PageType $pageType
     * @return
     */
    protected function create($pageType)
    {
        return $this
            ->apiClient
            ->post($this->endpoint, $this->payload($pageType->extract()));
    }

    /**
     * Updates an existing PageType
     * @param PageType $pageType
     * @return type
     */
    protected function update($pageType)
    {
        $updateEndpoint = $this->endpoint . $pageType->id;
        return $this
            ->apiClient
            ->post($updateEndpoint, $this->payload($pageType->extract()));
    }

    public function blueprint()
    {
        return new PageType([], $this);
    }

    public function setSiteToken($token)
    {
        $this->siteToken = $token;
        $this->endpoint .= $token . '/';
        return $this;
    }
}
