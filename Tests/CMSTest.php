<?php
namespace Tests;

use Dotenv\Dotenv;
use Meiosis\Amino;
use Meiosis\Exceptions\ObjectNotFoundException;
use PHPUnit\Framework\TestCase;

class CMSTest extends TestCase
{
    public static function setupBeforeClass()
    {
        $dotenv = new Dotenv(__DIR__.'/..');
        $dotenv->load();
    }

    public function testSiteCreation()
    {
        $this->assertTrue(true);
    }

    public function testSiteUpdate()
    {
        $this->assertTrue(true);
    }

    /**
     * @depends testPageUpdate
     **/
    public function testSiteDelete()
    {
        $this->assertTrue(true);
    }

    public function testPageCreation()
    {
        $this->assertTrue(true);
    }

    public function testPageUpdate()
    {
        $this->assertTrue(true);
    }

    public function testPageDelete()
    {
        $this->assertTrue(true);
    }
}
