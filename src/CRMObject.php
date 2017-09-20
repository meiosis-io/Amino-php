<?php
namespace Meiosis;

use Meiosis\Constants\Api;
use Meiosis\ApiClient\ApiClient;

abstract class CRMObject
{
    protected $token;
    protected $teamID;
    protected $data;
    protected $apiClient;

    // Instantiate the object with the API credentials, and build the client

    public function __construct($apikey, $teamID, $api_url)
    {
        $this->token  = urlencode($apikey);
        $this->teamID = urlencode($teamID);

        $this->data = (object) [];

        $this->apiClient = new ApiClient($api_url);

    }

    abstract public function find($identifier);

    abstract public function create($data);

    abstract public function saveChanges();

    abstract public function delete($identifier);

    public function exists()
    {
        // Return true or false based on populated...
        return ($this->id) ? true : false;
    }

    public function payload($data = [])
    {
        return array_merge([
            'api_token' => $this->token,
            'team'      => $this->teamID
        ], $data);
    }

    public function getDataArray()
    {
        return (array) $this->data;
    }

    public function __set($name, $value)
    {
        $this->data->{$name} = $value;
    }

    public function __get($name)
    {
        if (property_exists($this->data, $name)) {
            return $this->data->{$name};
        }

        return null;
    }
}
