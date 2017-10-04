<?php
namespace Tests;

use Dotenv\Dotenv;
use Meiosis\Amino;
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

    public function testTransactionCreation()
    {
        // Get the transaction
        $transaction = self::$amino->transactions()->blueprint();
        $transaction->customer = self::$customer;

        for ($i = 1; $i <= 10; $i++) {
            $item = new TransactionItem();
            $item->price = 5;
            $item->quantity = 2;
            $transaction->addItem($item);
        }

        $this->assertEquals($transaction->total, 100);
        // Save and check the return object
        $transaction->save();

        $this->assertNotNull($transaction->id);
    }
}
