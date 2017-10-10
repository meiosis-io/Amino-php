<?php
namespace Meiosis;

use Meiosis\Constants\Api;
use Meiosis\Endpoints\CMSSite;
use Meiosis\Endpoints\CMSPage;
use Meiosis\Endpoints\CMSPageType;
use Meiosis\Endpoints\CMSPageAttribute;
use Meiosis\Endpoints\CRMCustomer;
use Meiosis\Endpoints\CRMOrganization;
use Meiosis\Endpoints\CRMTransaction;
use Meiosis\ApiClient\ApiClient;
use Meiosis\Exceptions\InvalidEndpointException;
use Meiosis\Exceptions\ObjectNotPopulatedException;

class Amino
{
    const VERSION  = "0.2.0"; // SDK Version
    const API_VERSION  = "1"; // API Version

    /**
     * Api Key (created from app)
     * @var string
     */
    private $apikey;

    /**
     * TeamID for interactions
     * @var int
     */
    private $teamID;

    /**
     * Api Base URL
     * @var string
     */
    private $api_url;

    /**
     * Setup
     * @param int $apikey
     * @param string $teamID
     * @return void
     */
    public function __construct($apikey, $teamID)
    {
        $this->apikey = $apikey;
        $this->teamID = $teamID;
        $this->api_url = Api::API_BASEPATH;
    }

    /**
     * Override the default endpoint URL for testing
     * @param string $url
     * @return self
     */
    public function setCustomBaseUrl($url)
    {
        $this->api_url = $url;
        return $this;
    }

    /**
     * Fetch a CRMObject to work with customer data
     * @return CRMCustomer
     */
    public function customers()
    {
        return new CRMCustomer($this->apikey, $this->teamID, $this->api_url);
    }

    /**
     * Fetch a CRMObject to work with organization data
     * @return CRMOrganization
     */
    public function organizations()
    {
        return new CRMOrganization($this->apikey, $this->teamID, $this->api_url);
    }

    /**
     * Fetch a CRMObject to work with transactions data
     * @return CRMTransaction
     */
    public function transactions()
    {
        return new CRMTransaction($this->apikey, $this->teamID, $this->api_url);
    }

    /**
     * Fetch a CRMObject to work with Site data
     * @return CMSSite
     */
    public function sites()
    {
        return new CMSSite($this->apikey, $this->teamID, $this->api_url);
    }

    /**
     * Fetch a CRMObject to work with CMS Page data
     * @param string $siteToken
     * @return CMSPage
     */
    public function pages($siteToken)
    {
        $page = new CMSPage($this->apikey, $this->teamID, $this->api_url);
        $page->setSiteToken($siteToken);
        return $page;
    }

    /**
     * Fetch a CRMObject to work with CMS Page Types
     * @return CMSPageType
     */
    public function pageTypes($siteToken)
    {
        $type = new CMSPageType($this->apikey, $this->teamID, $this->api_url);
        $type->setSiteToken($siteToken);
        return $type;
    }

    /**
     * Fetch a CRMObject to work with CMS Page Attributes
     * @param string $pageType - Page Type ID
     * @return CMSPageAttribute
     */
    public function pageAttributes($pageType)
    {
        return new CMSPageAttribute($this->apikey, $this->teamID, $this->api_url, $pageType);
    }

    /**
     * Function for testing connectivity. Will ping configured base URL to ensure it's up.
     * This doesn't have much of a purpose outside of development to ensure your system
     * can talk to the api.
     * @return bool Connection Status
     */
    public function remoteTest()
    {
        $client = new ApiClient($this->api_url);
        $response = $client->get('', []);
        return (bool) $response;
    }
}
