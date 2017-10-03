<?php
namespace Tests;

use Dotenv\Dotenv;
use Meiosis\Amino;
use Meiosis\Exceptions\ObjectNotFoundException;
use PHPUnit\Framework\TestCase;

class OrganizationTest extends TestCase
{
    public static function setupBeforeClass()
    {
        $dotenv = new Dotenv(__DIR__.'/..');
        $dotenv->load();
    }

    public function testOrganizationCreation()
    {
        $orgData = [];

        $amino = new Amino(getenv('API_TOKEN'), getenv('API_TEAM'));
        $amino->setCustomBaseUrl(getenv('API_BASE_URL'));

        $organization = $amino->organizations()->blueprint();

        $organization->name = 'PhpUnit Intl, llc, co';
        $organization->save();

        $this->assertNotNull($organization->id);

        return $orgData = [
            'id'    => $organization->id,
            'name' => $organization->name
        ];
    }


    /**
     * @depends testOrganizationCreation
     */
    public function testOrganizationSearch(array $orgData)
    {
        $amino = new Amino(getenv('API_TOKEN'), getenv('API_TEAM'));
        $amino->setCustomBaseUrl(getenv('API_BASE_URL'));

        $organization = $amino->organizations()->find($orgData['id']);
        $this->assertEquals($organization->name, $orgData['name']);

        return $orgData;
    }

    /**
     * @depends testOrganizationCreation
     */
    public function testOrganizationUpdate(array $orgData)
    {
        $amino = new Amino(getenv('API_TOKEN'), getenv('API_TEAM'));
        $amino->setCustomBaseUrl(getenv('API_BASE_URL'));

        $organization = $amino->organizations()->find($orgData['id']);

        $newName = "PHPUNIT INTERNATIONAL UNLIMITED COMPANY";
        $organization->name = $newName;
        $organization->save();

        $this->assertEquals($organization->name, $newName);
    }

    /**
     * @depends testOrganizationSearch
     */
    public function testOrganizationDelete(array $orgData)
    {
        $amino = new Amino(getenv('API_TOKEN'), getenv('API_TEAM'));
        $amino->setCustomBaseUrl(getenv('API_BASE_URL'));

        $result = $amino->organizations()->delete($orgData['id']);
        $this->assertObjectHasAttribute('success', $result);

        return $orgData;
    }

    /**
     * @depends testOrganizationDelete
     */
    public function testFailedOrganizationSearch(array $orgData)
    {
        $this->expectException(ObjectNotFoundException::class);

        $amino = new Amino(getenv('API_TOKEN'), getenv('API_TEAM'));
        $amino->setCustomBaseUrl(getenv('API_BASE_URL'));
        $amino->organizations()->find($orgData['id']);
    }
}
