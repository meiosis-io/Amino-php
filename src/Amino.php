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
    const VERSION  = "0.4.2"; // SDK Version
    const API_VERSION  = "1"; // API Version

    /**
     * Api Key (created from app)
     * @var string
     */
    private $apiToken;

    /**
     * TeamID for interactions
     * @var int
     */
    private $teamToken;

    /**
     * Api Base URL
     * @var string
     */
    private $apiBase;

    /**
     * @var The Api Client
     */
    private $client;

    /**
     * Setup
     * @param int $apikey
     * @param string $teamID
     * @return void
     */
    public function __construct($apiToken, $teamToken)
    {
        $this->apiToken = $apiToken;
        $this->teamToken = $teamToken;
        $this->apiBase = Api::API_BASEPATH;

        $this->client = new ApiClient($this);
    }

    /**
     * Override the default endpoint URL for testing
     * @param string $url
     * @return self
     */
    public function setCustomBaseUrl($url)
    {
        $this->apiBase = $url;

        // ReInstance the Api Client
        $this->client = new ApiClient($this);

        return $this;
    }

    /**
     * Fetch a CRMObject to work with customer data
     * @return CRMCustomer
     */
    public function customers()
    {
        return new CRMCustomer($this);
    }

    /**
     * Fetch a CRMObject to work with organization data
     * @return CRMOrganization
     */
    public function organizations()
    {
        return new CRMOrganization($this);
    }

    /**
     * Fetch a CRMObject to work with transactions data
     * @return CRMTransaction
     */
    public function transactions()
    {
        return new CRMTransaction($this);
    }

    /**
     * Fetch a CRMObject to work with Site data
     * @return CMSSite
     */
    public function sites()
    {
        return new CMSSite($this);
    }

    /**
     * Fetch a CRMObject to work with CMS Page data
     * @param string $siteToken
     * @return CMSPage
     */
    public function pages($siteToken)
    {
        $page = new CMSPage($this);
        $page->setSiteToken($siteToken);
        return $page;
    }

    /**
     * Fetch a CRMObject to work with CMS Page Types
     * @return CMSPageType
     */
    public function pageTypes($siteToken)
    {
        $type = new CMSPageType($this);
        $type->setSiteToken($siteToken);
        return $type;
    }

    /**
     * Fetch a CRMObject to work with CMS Page Attributes
     * @param string $pageType - Page Type ID
     * @return CMSPageAttribute
     */
    public function pageAttributes($siteToken, $pageType)
    {
        return new CMSPageAttribute($this, $siteToken, $pageType);
    }

    /**
     * Function for testing connectivity. Will ping configured base URL to ensure it's up.
     * This doesn't have much of a purpose outside of development to ensure your system
     * can talk to the api.
     * @return bool Connection Status
     */
    public function remoteTest()
    {
        $response = $this->client()->get('', []);

        return (bool) $response;
    }

    /**
     * Get the Configured Api Token
     * @return string
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * Get the configured team Token
     * @return string
     */
    public function getTeamToken()
    {
        return $this->teamToken;
    }

    /**
     * Get the configured API Base URL
     * @return string
     */
    public function getApiBase()
    {
        return $this->apiBase;
    }

    /**
     * Get the API Client
     * @return ApiClient
     */
    public function client()
    {
        return $this->client;
    }

    public function __sleep()
    {
        return ['apiToken', 'teamToken', 'apiBase'];
    }

    public function __wakeup()
    {
        $this->client = new ApiClient($this);
    }
}
