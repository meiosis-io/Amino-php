<?php
namespace Tests;

use Dotenv\Dotenv;
use Meiosis\Amino;
use PHPUnit\Framework\TestCase;

class ConnectorTest extends TestCase
{
    public static function setupBeforeClass()
    {
        $dotenv = new Dotenv(__DIR__.'/..');
        $dotenv->load();
    }

    public function testApiVersion()
    {
        // Make sure we're still expecting the same version
        $this->assertEquals(Amino::API_VERSION, 1);
    }

    // Test that our connector works
    public function testConnector()
    {
        $amino = new Amino(getenv('API_TOKEN'), getenv('API_TEAM'));
        $this->assertEquals(Amino::API_VERSION, 1);
    }
}
