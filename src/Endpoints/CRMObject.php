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
     * Given an object, save it
     * @param BaseModel $object
     * @return type
     */
    public function save($object)
    {
        if (is_null($object->id)) {
            $result = $this->create($object);
        }

        if (!is_null($object->id)) {
            $result = $this->update($object);
        }

        return $this->find($result->id);
    }

    /**
     * Create a new BaseModel
     * @param BaseModel $object
     * @return
     */
    protected function create($object)
    {
        return $this
            ->apiClient
            ->post($this->endpoint, $this->payload($object->extract()));
    }

    /**
     * Updates an existing BaseModel
     * @param BaseModel $object
     * @return type
     */
    protected function update($object)
    {
        $updateEndpoint = $this->endpoint . $object->id;
        return $this
            ->apiClient
            ->post($updateEndpoint, $this->payload($object->extract()));
    }

    /**
     * Deletes an Existing Object
     * @param Object|String $identifier
     * @return type
     */
    public function delete($identifier)
    {
        if (gettype($identifier) == 'string') {
            $deleteEndpoint = $this->endpoint . $identifier;
        }

        if ($identifier instanceof BaseModel) {
            $deleteEndpoint = $this->endpoint . $identifier->id;
        }

        return $this
            ->apiClient
            ->delete($deleteEndpoint, $this->payload());
    }
}
