<?php
namespace Meiosis\Endpoints;

use Meiosis\Constants\Api;
use Meiosis\Endpoints\CRMObject;
use Meiosis\Endpoints\CRMObjectInterface;
use Meiosis\Exceptions\ObjectNotFoundException;
use Meiosis\Exceptions\ObjectNotPopulatedException;
use Meiosis\Models\PageType;

/**
 * Class for working with the /cms/page-type endpoint
 */
class CMSPageType extends CRMObject implements CRMObjectInterface
{
    protected $endpoint = 'cms/page-type/';
    protected $siteToken = '';
    protected static $returnType = PageType::class;

    /**
     * Set the site to query against
     * @param string $token
     * @return CMSPageType
     */
    public function setSiteToken($token)
    {
        $this->siteToken = $token;
        $this->endpoint .= $token . '/';
        return $this;
    }
}
