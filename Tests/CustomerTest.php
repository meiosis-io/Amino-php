<?php
namespace Tests;

use Dotenv\Dotenv;
use Meiosis\Amino;
use Meiosis\Exceptions\ObjectNotFoundException;
use Meiosis\Exceptions\ObjectValidationFailedException;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    public static $amino = null;

    public static function setupBeforeClass(): void
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

        return $customer;
    }

    public function testCustomerValidationFailure()
    {
        $customer = self::$amino->customers()->blueprint();

        $this->expectException(ObjectValidationFailedException::class);
        $customer->save();
    }

    /**
     * @depends testCustomerCreation
     */
    public function testCustomerInteraction($customer)
    {
        self::$amino->customers()->trackInteraction($customer, 'TestSuite', 'Test Interaction');
        $customer->refresh();

        $this->assertObjectHasAttribute('source', $customer->interactions[0]);

        // Test that customers from a blueprint are saved when tracking.
        $newCustomer = self::$amino->customers()->blueprint();
        $newCustomer->email = 'phpunitcustomertest2@localhost.dev';
        $newCustomer->first = "Test Account2";
        self::$amino->customers()->trackInteraction($newCustomer, 'TestSuite', 'Test Interaction');
        $newCustomer->refresh();
        $this->assertNotNull($newCustomer->id);
        $this->assertObjectHasAttribute('source', $newCustomer->interactions[0]);

        // Delete it...
        self::$amino->customers()->delete($newCustomer);

        return $customer;
    }

    /**
     * @depends testCustomerInteraction
     */
    public function testCustomerSearch($customer)
    {

        $foundCustomer = self::$amino->customers()->find($customer->id);
        $this->assertEquals($foundCustomer->id, $customer->id);

        $foundCustomers = self::$amino->customers()->search(['email' => 'phpunitcustomertest']);
        $this->assertEquals($foundCustomers[0]->id, $customer->id);

        return $customer;
    }

    /**
     * @depends testCustomerCreation
     */
    public function testCustomerUpdate($customer)
    {
        $newName = "NewName";
        $customer->first = $newName;
        $customer->save();
        $this->assertEquals($customer->first, $newName);
    }

    /**
     * @depends testCustomerSearch
     */
    public function testCustomerDelete($customer)
    {
        $result = self::$amino->customers()->delete($customer->id);
        $this->assertObjectHasAttribute('success', $result);

        return $customer;
    }

    /**
     * @depends testCustomerDelete
     */
    public function testFailedCustomerSearch($customer)
    {
        $this->expectException(ObjectNotFoundException::class);

        self::$amino->customers()->find($customer->id);
    }
}
