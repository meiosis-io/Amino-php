<?php
namespace Meiosis\Endpoints;

use Meiosis\Constants\Api;
use Meiosis\Endpoints\CRMObject;
use Meiosis\Endpoints\CRMObjectInterface;
use Meiosis\Exceptions\ObjectNotFoundException;
use Meiosis\Exceptions\ObjectNotPopulatedException;
use Meiosis\Models\Page;

/**
 * Class for working with the /cms/site/{siteid}/page endpoint
 */
class CMSPage extends CRMObject implements CRMObjectInterface
{
    protected $endpoint = 'cms/site/';
    protected $siteToken = '';
    protected static $returnType = Page::class;

    /**
     * Fetch the page hierarchy for a given page, or the site as a whole
     * @param string|bool $pageID
     * @return array of Page Objects
     */
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

    /**
     * Perform a page search based on a slug / url string
     * @param string $slug
     * @return array of Page objects
     */
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
     * Search by Attributes
     * @param array $attributes
     * @return array of Page Objects
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

    /**
     * Set the site that should be used
     * @param string $token
     * @return CRMPage
     */
    public function setSiteToken($token)
    {
        $this->siteToken = $token;
        $this->endpoint .= $token . '/page/';
        return $this;
    }
}
