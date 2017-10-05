<?php
namespace Tests;

use Dotenv\Dotenv;
use Meiosis\Amino;
use Meiosis\Exceptions\ObjectNotFoundException;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    public static $amino = null;

    public static function setupBeforeClass()
    {
        $dotenv = new Dotenv(__DIR__.'/..');
        $dotenv->load();

        // Try to find a customer
        $amino = new Amino(getenv('API_TOKEN'), getenv('API_TEAM'));
        $amino->setCustomBaseUrl(getenv('API_BASE_URL'));

        self::$amino = $amino;
    }

    public function testCustomerCreation()
    {
        $customerData = [];

        $customer = self::$amino->customers()->blueprint();

        $customer->email = 'phpunitcustomertest@localhost.dev';
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
        $customer = self::$amino->customers()->find($customerData['id']);

        self::$amino->customers()->trackInteraction($customer, 'TestSuite', 'Test Interaction');
        $customer->refresh();

        $this->assertObjectHasAttribute('source', $customer->interactions[0]);
    }

    /**
     * @depends testCustomerCreation
     */
    public function testCustomerSearch(array $customerData)
    {

        $customer = self::$amino->customers()->find($customerData['id']);
        $this->assertEquals($customer->email, $customerData['email']);

        return $customerData;
    }

    /**
     * @depends testCustomerCreation
     */
    public function testCustomerUpdate(array $customerData)
    {

        $customer = self::$amino->customers()->find($customerData['id']);

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
        $result = self::$amino->customers()->delete($customerData['id']);
        $this->assertObjectHasAttribute('success', $result);

        return $customerData;
    }

    /**
     * @depends testCustomerDelete
     */
    public function testFailedCustomerSearch(array $customerData)
    {
        $this->expectException(ObjectNotFoundException::class);

        $customer = self::$amino->customers()->find($customerData['id']);
    }
}
