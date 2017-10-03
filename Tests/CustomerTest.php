<?php
namespace Tests;

use Dotenv\Dotenv;
use Meiosis\Amino;
use Meiosis\Exceptions\ObjectNotFoundException;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    public static function setupBeforeClass()
    {
        $dotenv = new Dotenv(__DIR__.'/..');
        $dotenv->load();
    }

    public function testCustomerCreation()
    {
        $customerData = [];

        $amino = new Amino(getenv('API_TOKEN'), getenv('API_TEAM'));
        $amino->setCustomBaseUrl(getenv('API_BASE_URL'));

        $customer = $amino->customers()->blueprint();

        $customer->email = 'phpunittestcase@localhost.dev';
        $customer->first = "Test Account";
        $customer->save();

        $this->assertNotNull($customer->id);

        return $customerData = [
            'id'    => $customer->id,
            'email' => $customer->email
        ];
    }

    /**
     * @depends testCustomerCreation
     */
    public function testCustomerInteraction(array $customerData)
    {
        $amino = new Amino(getenv('API_TOKEN'), getenv('API_TEAM'));
        $amino->setCustomBaseUrl(getenv('API_BASE_URL'));
        $customer = $amino->customers()->find($customerData['id']);

        $amino->customers()->trackInteraction($customer, 'TestSuite', 'Test Interaction');
        $customer->refresh();

        $this->assertObjectHasAttribute('source', $customer->interactions[0]);
    }

    /**
     * @depends testCustomerCreation
     */
    public function testCustomerSearch(array $customerData)
    {
        $amino = new Amino(getenv('API_TOKEN'), getenv('API_TEAM'));
        $amino->setCustomBaseUrl(getenv('API_BASE_URL'));

        $customer = $amino->customers()->find($customerData['id']);
        $this->assertEquals($customer->email, $customerData['email']);

        return $customerData;
    }

    /**
     * @depends testCustomerCreation
     */
    public function testCustomerUpdate(array $customerData)
    {
        $amino = new Amino(getenv('API_TOKEN'), getenv('API_TEAM'));
        $amino->setCustomBaseUrl(getenv('API_BASE_URL'));

        $customer = $amino->customers()->find($customerData['id']);

        $newName = "NewName";
        $customer->first = $newName;
        $customer->save();

        $this->assertEquals($customer->first, $newName);
    }

    /**
     * @depends testCustomerSearch
     */
    public function testCustomerDelete(array $customerData)
    {
        $amino = new Amino(getenv('API_TOKEN'), getenv('API_TEAM'));
        $amino->setCustomBaseUrl(getenv('API_BASE_URL'));
        $result = $amino->customers()->delete($customerData['id']);
        $this->assertObjectHasAttribute('success', $result);

        return $customerData;
    }

    /**
     * @depends testCustomerDelete
     */
    public function testFailedCustomerSearch(array $customerData)
    {
        $this->expectException(ObjectNotFoundException::class);

        $amino = new Amino(getenv('API_TOKEN'), getenv('API_TEAM'));
        $amino->setCustomBaseUrl(getenv('API_BASE_URL'));
        $customer = $amino->customers()->find($customerData['id']);
    }
}
