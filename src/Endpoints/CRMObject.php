<?php
namespace Meiosis\Endpoints;

use Meiosis\Constants\Api;
use Meiosis\ApiClient\ApiClient;

abstract class CRMObject
{
    protected $token;
    protected $teamID;
    protected $apiClient;
    protected $apiUrl;

    // Instantiate the object with the API credentials, and build the client
    public function __construct($apikey, $teamID, $api_url)
    {
        $this->token  = urlencode($apikey);
        $this->teamID = urlencode($teamID);
        $this->apiUrl = $api_url;
        $this->apiClient = new ApiClient($this->apiUrl);
    }

    /**
     * Build the payload payload to supply to the APIClient
     * @param array $data
     * @return array
     */
    public function payload(array $data = [])
    {
        return array_merge([
            'api_token' => $this->token,
            'team'      => $this->teamID
        ], $data);
    }
}
