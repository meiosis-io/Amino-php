<?php
namespace Tests;

use Dotenv\Dotenv;
use Meiosis\Amino;
use Meiosis\Exceptions\UnknownApiException;
use Meiosis\Exceptions\UseOtherMethodException;
use Meiosis\Models\Customer;
use PHPUnit\Framework\TestCase;

class ConnectorTest extends TestCase
{
    public static function setupBeforeClass()
    {
        $dotenv = new Dotenv(__DIR__.'/..');
        $dotenv->load();
    }

    // Test that our connector works
    public function testConnector()
    {
        $amino = new Amino(getenv('API_TOKEN'), getenv('API_TEAM'));
        $amino->setCustomBaseUrl(getenv('API_BASE_URL'));

        $this->assertEquals(Amino::API_VERSION, 1);
        $this->assertTrue($amino->remoteTest());

        $this->expectException(UnknownApiException::class);
        $return = $amino
            ->setCustomBaseUrl('http://httpbin.org/status/500')
            ->remoteTest();
    }

    public function testModelSaveExceptions()
    {
        $amino = new Amino(getenv('API_TOKEN'), getenv('API_TEAM'));
        $amino->setCustomBaseUrl(getenv('API_BASE_URL'));
        $customer = new Customer();
        $this->expectException(UseOtherMethodException::class);
        $customer->save();
    }

    public function testModelSerialization()
    {
        $amino = new Amino(getenv('API_TOKEN'), getenv('API_TEAM'));
        $amino->setCustomBaseUrl(getenv('API_BASE_URL'));
        $customer = $amino->customers()->blueprint();
        $customer->email = 'phpunit-seralize-test@gmail.com';

        $scust = serialize($customer);
        $dcust = unserialize($scust);
        $dcust->save();

        $this->assertNotNull($dcust->id);
        $this->assertEquals($dcust->email, $customer->email);
        $amino->customers()->delete($dcust);
    }
}
