<?php
namespace Tests;

use Dotenv\Dotenv;
use Meiosis\Amino;
use Meiosis\Exceptions\InvalidEndpointException;
use Meiosis\Exceptions\ObjectNotFoundException;
use Meiosis\Models\TransactionItem;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{

    public static $customer = null;
    public static $amino = null;

    public static function setupBeforeClass()
    {
        $dotenv = new Dotenv(__DIR__.'/..');
        $dotenv->load();

        // Try to find a customer
        $amino = new Amino(getenv('API_TOKEN'), getenv('API_TEAM'));
        $amino->setCustomBaseUrl(getenv('API_BASE_URL'));

        try {
            $customer = $amino->customers()->find('phpunittransactiontest@localhost.dev');
        } catch (ObjectNotFoundException $e) {
            $customer = $amino->customers()->blueprint();
            $customer->email = 'phpunittransactiontest@localhost.dev';
            $customer->first = "Test Account";
            $customer->save();
        }

        self::$amino = $amino;
        self::$customer = $customer;
    }

    public function testSearchNotImplemented()
    {
        $this->expectException(InvalidEndpointException::class);
        self::$amino->transactions()->search([]);
    }

    public function testTransactionCreation()
    {
        // Get the transaction
        $transaction = self::$amino->transactions()->blueprint();
        $transaction->customer = self::$customer;

        for ($i = 1; $i <= 10; $i++) {
            $item = new TransactionItem();
            $item->id = 'item-'.$i;
            $item->price = 5;
            $item->quantity = 2;
            $transaction->addItem($item);
        }

        $this->assertEquals($transaction->total, 100);
        // Save and check the return object
        $transaction->save();

        $this->assertNotNull($transaction->id);

        // Ensure that items are of the proper type
        $this->assertTrue($transaction->items[0] instanceof TransactionItem);

        return $transaction;
    }

    /**
     * @depends testTransactionCreation
     */
    public function testTransactionSearch($transaction)
    {
        // This should fail, as transactions can only be created, never updated.
        $this->expectException(InvalidEndpointException::class);
        // Update the transaction
        $transaction->save();
    }

    /**
     * @depends testTransactionCreation
     */
    public function testVoidTransaction($transaction)
    {
        // Transaction total should be negative, and new ID generated.
        $oldID = $transaction->id;
        $oldTotal = $transaction->total;

        // Void the transaction
        $transaction->void();

        $this->assertNotEquals($oldID, $transaction->id);
        $this->assertEquals($oldTotal * -1, $transaction->total);

        return $transaction;
    }

    /**
     * @depends testVoidTransaction
     */
    public function testTransactionDelete($transaction)
    {
        $result = self::$amino->transactions()->delete($transaction->id);
        $this->assertObjectHasAttribute('success', $result);
    }
}
