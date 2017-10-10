<?php
namespace Tests;

use Dotenv\Dotenv;
use Meiosis\Amino;
use Meiosis\Exceptions\ObjectNotFoundException;
use PHPUnit\Framework\TestCase;

class OrganizationTest extends TestCase
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

    public function testOrganizationCreation()
    {
        $orgData = [];

        $organization = self::$amino->organizations()->blueprint();

        $organization->name = 'PhpUnit Intl, llc, co';
        $organization->save();

        $this->assertNotNull($organization->id);

        return $organization;
    }


    /**
     * @depends testOrganizationCreation
     */
    public function testOrganizationSearch($organization)
    {
        $organization = self::$amino->organizations()->find($organization->id);
        $this->assertEquals($organization->name, $organization->name);

        $foundOrgs = self::$amino->organizations()->search(['name' => 'PhpUnit Intl']);
        $this->assertEquals($foundOrgs[0]->id, $organization->id);

        return $organization;
    }

    /**
     * @depends testOrganizationCreation
     */
    public function testOrganizationUpdate($organization)
    {
        $newName = "PHPUNIT INTERNATIONAL UNLIMITED COMPANY";
        $organization->name = $newName;
        $organization->save();

        $this->assertEquals($organization->name, $newName);
    }

    /**
     * @depends testOrganizationSearch
     */
    public function testOrganizationDelete($organization)
    {
        $result = self::$amino->organizations()->delete($organization->id);
        $this->assertObjectHasAttribute('success', $result);

        return $organization;
    }

    /**
     * @depends testOrganizationDelete
     */
    public function testFailedOrganizationSearch($organization)
    {
        $this->expectException(ObjectNotFoundException::class);
        self::$amino->organizations()->find($organization->id);
    }
}
