<?php
namespace Meiosis\Endpoints;

use Meiosis\ApiClient\ApiClient;
use Meiosis\Constants\Api;
use Meiosis\Models\BaseModel;

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

    /**
     * Deletes an Existing Object
     * @param Object|String $identifier
     * @return type
     */
    public function delete($identifier)
    {
        $type = gettype($identifier);

        if (gettype($identifier) == 'string') {
            $deleteEndpoint = $this->endpoint . $identifier;
        }

        if ($identif instanceof BaseModel) {
            $deleteEndpoint = $this->endpoint . $identifier->id;
        }

        return $this
            ->apiClient
            ->delete($deleteEndpoint, $this->payload());
    }
}
