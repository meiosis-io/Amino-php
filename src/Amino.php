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
    const VERSION  = "0.1.6"; // SDK Version
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

    public function customers()
    {
        return new CRMCustomer($this->apikey, $this->teamID, $this->api_url);
    }

    public function organizations()
    {
        return new CRMOrganization($this->apikey, $this->teamID, $this->api_url);
    }

    public function transactions()
    {
        return new CRMTransaction($this->apikey, $this->teamID, $this->api_url);
    }

    public function sites()
    {
        return new CMSSite($this->apikey, $this->teamID, $this->api_url);
    }

    public function pages($siteToken)
    {
        $page = new CMSPage($this->apikey, $this->teamID, $this->api_url);
        $page->setSiteToken($siteToken);
        return $page;
    }

    public function pageTypes()
    {
        return new CMSPageType($this->apikey, $this->teamID, $this->api_url);
    }

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
