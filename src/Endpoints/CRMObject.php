<?php
namespace Meiosis\Endpoints;

use Meiosis\Constants\Api;
use Meiosis\ApiClient\ApiClient;

abstract class CRMObject
{
    protected $token;
    protected $teamID;
    protected $apiClient;

    // Instantiate the object with the API credentials, and build the client
    public function __construct($apikey, $teamID, $api_url)
    {
        $this->token  = urlencode($apikey);
        $this->teamID = urlencode($teamID);
        $this->apiClient = new ApiClient($api_url);
    }

    /**
     * Given an identifier for our object, find and return exactly one
     * @param string $identifier
     * @return type
     */
    abstract public function find($identifier);

    /**
     * Retun an empty instance of the appropriate Model
     * @return type
     */
    abstract public function blueprint();

    /**
     * Given an object, save or update it
     * @param object $object
     * @return type
     */
    abstract public function save($object);

    abstract public function delete($item);

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
