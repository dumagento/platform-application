<?php

namespace Acme\Bundle\TestsBundle\Tests\Selenium;

use Acme\Bundle\TestsBundle\Pages\BAP\Login;

class NavigationTest extends \PHPUnit_Extensions_Selenium2TestCase
{
    protected $coverageScriptUrl = PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL_COVERAGE;

    protected function setUp()
    {
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort(intval(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT));
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM2_BROWSER);
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
    }

    protected function tearDown()
    {
        $this->cookie()->clear();
    }

    /**
     * Test for User tab navigation
     */
    public function testUserTab()
    {
        $login = new Login($this);
        $login->setUsername(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN)
            ->setPassword(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS)
            ->submit()
            ->openNavigation()
            ->tab('Users')
            ->menu('Users')
            ->assertElementPresent("//table[@class='grid table-hover table table-bordered table-condensed']/tbody");

        $login->openNavigation()
            ->tab('Users')
            ->menu('Roles')
            ->assertElementPresent("//table[@class='grid table-hover table table-bordered table-condensed']/tbody");

        $login->openNavigation()
            ->tab('Users')
            ->menu('Groups')
            ->assertElementPresent("//table[@class='grid table-hover table table-bordered table-condensed']/tbody");
    }

    /**
     * Test Pinbar History
     *
     * @depends testUserTab
     */
    public function testPinbarHistory()
    {
        $login = new Login($this);
        $login->setUsername(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN)
            ->setPassword(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS)
            ->submit();
        //Open History pinbar dropdown
        $login->byXPath("//div[@class='pin-menus dropdown dropdown-close-prevent']/span")->click();
        $login->waitForAjax();
        $login->assertElementPresent("//div[@class='tabbable tabs-left']");
        $login->byXPath("//div[@class='tabbable tabs-left']//a[contains(., 'History')]")->click();
        $login->waitForAjax();
        //Check that user, group and roles pages added
        $login->assertElementPresent(
            "//div[@id='history-content'][//a[contains(., 'Users')]][//a[contains(., 'Roles')]][//a[contains(., 'Groups')]]",
            'Not found in History tab'
        );
    }

    /**
     * Test Pinbar Most Viewed
     *
     * @depends testUserTab
     */
    public function testPinbarMostViewed()
    {
        $login = new Login($this);
        $login->setUsername(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN)
            ->setPassword(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS)
            ->submit();
        //Open Most viewed pinbar dropdown
        $login->byXPath("//div[@class='pin-menus dropdown dropdown-close-prevent']/span")->click();
        $login->waitForAjax();
        $login->assertElementPresent("//div[@class='tabbable tabs-left']");
        $login->byXPath("//div[@class='tabbable tabs-left']//a[contains(., 'Most Viewed')]")->click();
        $login->waitForAjax();
        //Check that user, group and roles pages added
        $login->assertElementPresent(
            "//div[@id='most-viewed-content'][//a[contains(., 'Users')]][//a[contains(., 'Roles')]][//a[contains(., 'Groups')]]",
            'Not found in Most Viewed section'
        );
    }

    /**
     * Test Pinbar Most Viewed
     *
     */
    public function testPinbarFavorites()
    {
        $login = new Login($this);
        $login->setUsername(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN)
            ->setPassword(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS)
            ->submit()
            ->openGroups();
        //Add Groups page to favorites
        $login->byXPath("//button[@class='btn favorite-button']")->click();
        //Open pinbar dropdown Favorites
        $login->byXPath("//div[@class='pin-menus dropdown dropdown-close-prevent']/span")->click();
        $login->waitForAjax();
        $login->assertElementPresent("//div[@class='tabbable tabs-left']");
        $login->byXPath("//div[@class='tabbable tabs-left']//a[contains(., 'Favorites')]")->click();
        $login->waitForAjax();
        //Check that page is added to favorites
        $login->assertElementPresent("//div[@id='favorites-content' and @class='tab-pane active']");
        $login->waitForAjax();
        $login->assertElementPresent(
            "//div[@id='favorites-content'][//span[contains(., 'Groups overview - User Management')]]",
            'Not found in favorites section'
        );
        //Remove Groups page from favorites
        $login->byXPath("//div[@id='favorites-content'][//span[contains(., 'Groups overview - User Management')]]//button[@class='close']")->click();
        $login->waitForAjax();
        //Check that page is deleted from favorites
        $login->assertElementNotPresent(
            "//div[@id='favorites-content'][//span[contains(., 'Groups overview - User Management')]]",
            'Not found in favorites section'
        );
    }

    public function testTabs()
    {
        $login = new Login($this);
        $login->setUsername(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN)
            ->setPassword(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS)
            ->submit()
            ->openUsers();
        //Minimize page to pinbar tabs
        $login->byXPath("//div[@class='top-action-box']//button[@class='btn minimize-button']")->click();
        $login->waitForAjax();
        $login->assertElementPresent(
            "//div[@class='list-bar']//a[contains(., 'Users overview - User Management')]",
            'Element does not minimised to pinbar tab'
        );
    }

    public function testSearchTab()
    {
        $login = new Login($this);
        $login->setUsername(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN)
            ->setPassword(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS)
            ->submit()
            ->openNavigation()
            ->tab('Search')
            ->menu('Advanced search')
            ->assertElementPresent(
                "//div[@class='container-fluid']//div[@class='search_stats alert alert-info']",
                'Element does not present on page'
            );
    }

    public function testSimpleSearch()
    {
        $login = new Login($this);
        $login->setUsername(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN)
            ->setPassword(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS)
            ->submit()
            ->assertElementPresent(
                "//div[@id='search-div']//input[@id='search-bar-search']",
                'Simple search does not available'
            );
    }
}
