<?php
namespace Tests;

use Dotenv\Dotenv;
use Meiosis\Amino;
use Meiosis\Exceptions\ObjectNotFoundException;
use PHPUnit\Framework\TestCase;

class CMSTest extends TestCase
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

    public function testSiteCreation()
    {
        $site = self::$amino->sites()->blueprint();
        $site->name = "PHPUnit Test Site";
        $site->domains = "localhost";
        $site->description = "Some Test Site";
        $site->save();

        $this->assertNotNull($site->id);
        $this->assertEquals($site->name, 'PHPUnit Test Site');

        return $site;
    }

    /**
     * @depends testSiteCreation
     */
    public function testSiteUpdate($site)
    {
        $newDesc = "SomeNewDesc";
        $oldName = $site->name;

        $site->description = $newDesc;
        $site->save();

        $this->assertEquals($site->description, $newDesc);
        $this->assertEquals($site->name, $oldName);

        return $site;
    }

    /**
     * @depends testSiteUpdate
     */
    public function testPageTypeCreationAndUpdate($site)
    {
        $newType = self::$amino->pageTypes()
            ->setSiteToken($site->id)
            ->blueprint();

        $newType->name = "Custom Attribute";
        $newType->save();
        $this->assertNotNull($newType->id);

        $newType->name = "Attribute, Custom";
        $newType->save();

        return $newType;
    }

    /**
     * @depends testPageTypeCreationAndUpdate
     */
    public function testPageTypeAttributeCreation($pageType)
    {
        // Load All attributes
        $attributes = self::$amino->pageAttributes($pageType->id);
        $newAttribute = $attributes->blueprint();

        $newAttribute->name = "Test Attribute";
        $newAttribute->type = "text";
        $newAttribute->save();

        $this->assertNotNull($newAttribute->id);

        $newAttribute->type = "rich";
        $newAttribute->save();

        $this->assertEquals($newAttribute->type, 'rich');

        return $newAttribute;
    }

    /**
     * @depends testPageTypeCreationAndUpdate
     * @depends testPageTypeAttributeCreation
     **/
    public function testPageAttributeListAndSearch($pageType, $attribute)
    {
        $crmObject = self::$amino->pageAttributes($pageType->id);
        $this->assertEquals($crmObject->all()[0]->name, $attribute->name);

        // Null Search
        $attrSearch = $crmObject->search('name', 'Banana Splits');
        $this->assertNull($attrSearch);

        // Good search
        $attrSearch = $crmObject->search('name', 'Test Attribute');
        $this->assertEquals($attrSearch->name, $attribute->name);

        // Not Found
        $this->expectException(ObjectNotFoundException::class);
        $attrSearch = $crmObject->find('INVALID');
        var_dump($attrSearch);
    }

    /**
     * @depends testPageTypeCreationAndUpdate
     * @depends testPageTypeAttributeCreation
     **/
    public function testPageAttributeDelete($pageType, $attribute)
    {
        $result = self::$amino->pageAttributes($pageType->id)->delete($attribute->id);
        $this->assertObjectHasAttribute('message', $result);
    }

    /**
     * @depends testPageTypeCreationAndUpdate
     * @depends testSiteCreation
     **/
    public function testPageTypeDelete($pageType, $site)
    {
        $result = self::$amino->pageTypes()->setSiteToken($site->id)->delete($pageType);
        $this->assertObjectHasAttribute('message', $result);
    }

    /**
     * @depends testSiteUpdate
     */
    public function testPageCreation($site)
    {
        $page = self::$amino->pages($site->id)->blueprint();
        $page->name = "PHPUnit Test page";
        $page->excerpt = "Test Page";
        $page->content = 'Hello';
        $page->save();

        $this->assertNotNull($page->id);
        $this->assertEquals($page->name, "PHPUnit Test page");

        return $page;
    }

    /**
     * @depends testPageCreation
     */
    public function testPageUpdate($page)
    {
        $oldName = $page->name;
        $oldID = $page->id;
        $page->name = "Some New Name";
        $page->save();

        $this->assertNotEquals($oldName, $page->name);
        $this->assertEquals($oldID, $page->id);

        return $page;
    }

    /**
     * @depends testPageUpdate
     * @depends testSiteUpdate
     */
    public function testPageHierarchy($page, $site)
    {
        $pages = self::$amino->pages($site->id);

        $spage = $pages->blueprint();
        $spage->name = "PHPUnit Test page2";
        $spage->excerpt = "Test Page2";
        $spage->content = 'Hello2';
        $spage->save();

        $list = $pages->getHierarchy();
        $this->assertEquals(count($list), 2);

        $list = $pages->getHierarchy($page->id);
        $this->assertEquals(count($list), 1);
        $pages->delete($spage);
    }

    /**
     * @depends testPageUpdate
     * @depends testSiteUpdate
     */
    public function testPageSearches($page, $site)
    {
        // Slug Search....
        $pages = self::$amino->pages($site->id);
        $found = $pages->bySlug($page->slug);
        $this->assertEquals($found[0]->id, $page->id);

        // Attribute search
        $found = $pages->byAttributes(['name' => 'Some']);
        $this->assertEquals($found[0]->id, $page->id);
    }

    /**
     * @depends testPageUpdate
     * @depends testSiteUpdate
     */
    public function testPageDelete($page, $site)
    {
        // By ID
        $result = self::$amino->pages($site->id)->delete($page->id);
        $this->assertObjectHasAttribute('success', $result);

        $this->expectException(ObjectNotFoundException::class);

        // By Object
        self::$amino->pages($site->id)->delete($page);
    }

    /**
     * @depends testSiteUpdate
     */
    public function testSiteDelete($site)
    {
        $result = self::$amino->sites()->delete($site->id);
        $this->assertObjectHasAttribute('message', $result);
    }
}
