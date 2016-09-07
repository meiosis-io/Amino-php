<?php
namespace Meiosis;

use Meiosis\Requests\Customer;

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
    }

    public function customer()
    {
        return new Customer();
    }
}
