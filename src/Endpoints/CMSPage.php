<?php
namespace Meiosis\Endpoints;

use Meiosis\CRMObject;
use Meiosis\Constants\Api;
use Meiosis\Exceptions\ObjectNotPopulatedException;
use Meiosis\Exceptions\ObjectNotFoundException;

class CMSPage extends CRMObject
{
    private $endpoint = 'cms/';
    private $token = '';
    private $data = null;

    public function byId($pageID)
    {
        try {
            $this->data = $this->apiClient->get(
                "{$this->endpoint}/{$this->token}/page/{$pageID}",
                $this->payload()
            );
        } catch (ObjectNotFoundException $e) {
            // Nothing found.
        }

        return $this;
    }

     /**
     * Extract the private data
     * @return type
     */
    private function extract()
    {
        return $this->data;
    }

    public function setSiteToken($token)
    {
        $this->token = $token;
        return $token;
    }
}
