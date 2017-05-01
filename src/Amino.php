<?php
namespace Meiosis;

use Meiosis\Constants\Api;
use Meiosis\Endpoints\Customer;
use Meiosis\Endpoints\Organization;
use Meiosis\Endpoints\Transaction;
use Meiosis\Exceptions\InvalidEndpointException;
use Meiosis\Exceptions\ObjectNotPopulatedException;

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

    public function customer($identifier)
    {
        $customer = new Customer($this->apikey, $this->teamID, $this->api_url);
        return $customer->find($identifier);
    }

    public function createCustomer($fields)
    {
        $customer = new Customer($this->apikey, $this->teamID, $this->api_url);
        return $customer->create($fields);
    }

    public function organization($identifier)
    {
        $organization = new Organization($this->apikey, $this->teamID, $this->api_url);
        return $organization->find($identifier);
    }

    public function searchOrganizations($data)
    {
        $organization = new Organization($this->apikey, $this->teamID, $this->api_url);
        return $organization->search($data);
    }

    public function createOrganization($fields)
    {
        $organization = new Organization($this->apikey, $this->teamID, $this->api_url);
        return $organization->create($fields);
    }

    public function recordTransaction(Customer $customer, array $transactionData)
    {
        $transaction = new Transaction($this->apikey, $this->teamID, $this->api_url);
        if (! $customer->exists()) {
            throw new ObjectNotPopulatedException;
        }

        $data = [
            'customer' => $customer,
            'details'  => $transactionData
        ];

        return $transaction->create($data);
    }

    public function transaction($transactionId)
    {
        $transaction = new Transaction($this->apikey, $this->teamID, $this->api_url);
        return $transaction->find($transactionId);
    }
}
