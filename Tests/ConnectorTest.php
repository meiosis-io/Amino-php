<?php
namespace Tests;

use Meiosis\Amino;
use PHPUnit\Framework\TestCase as BaseTestCase;

class ConnectorTest extends BaseTestCase
{
    public function testConnector()
    {
        // $amino = new Amino();
        $this->assertEquals(Amino::API_VERSION, 1);
    }
}
