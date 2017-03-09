<?php
namespace Meiosis;

use Meiosis\Constants\Api;
use Meiosis\Exceptions\InvalidEndpointException;

class Amino
{

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

    public function endpoint($type)
    {
        $type = ucfirst($type);
        $class = "\Meiosis\Endpoints\{$type}";

        if (! class_exists($class)) {
            throw new InvalidEndpointException("Could not find the {$type} endpoint class");
        }

        return new $class;
    }
}
