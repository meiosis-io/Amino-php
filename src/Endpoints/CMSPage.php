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

        try {
            $this->data = $this->apiClient->get(
                $this->endpoint . $this->siteToken . '/page/' . $pageID,
                $this->payload()
            );
        } catch (ObjectNotFoundException $e) {
            throw new \Exception($e);
        }
        return $this;
    }

    public function bySlug($slug)
    {

        try {
            $this->data = $this->apiClient->get(
                $this->endpoint . $this->siteToken . '/page/',
                $this->payload([
                    'slug' => $slug
                ])
            );
        } catch (ObjectNotFoundException $e) {
            throw new \Exception($e);
        }
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
