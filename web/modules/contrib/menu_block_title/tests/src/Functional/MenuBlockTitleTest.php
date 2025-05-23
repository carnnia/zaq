<?php

namespace Drupal\Tests\menu_block_title\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Class SettingsPageTest.
 *
 * @package Drupal\Tests\menu_block_title\Functional
 *
 * @group menu_block_title
 */
class MenuBlockTitleTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['menu_block_title_test'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Permissions for user that will be logged-in for test.
   *
   * @var array
   */
  protected array $userPermissions = [
    'access content',
  ];

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function setUp(): void {
    parent::setUp();
    $account = $this->drupalCreateUser($this->userPermissions);
    $this->drupalLogin($account);
  }

  /**
   * Tests that a menu block title matches the expected title and link.
   *
   * @param string $path
   *   Path to target node page.
   * @param string $page_title
   *   Expected menu block title.
   * @param string $href
   *   Expected menu block title link destination.
   *
   * @throws \Behat\Mink\Exception\ElementHtmlException
   * @throws \Behat\Mink\Exception\ElementNotFoundException
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  protected function assertMenuBlockTitle(string $path = '/node/2', string $page_title = 'Test page title for top level nav', string $href = '/node/1'): void {
    $this->drupalGet($path);
    $this->assertSession()->elementContains('css', 'h2#block-sidebar-nav-main-menu', $page_title);
    $xpath = $this->assertSession()
      ->buildXPathQuery('//h2[@id="block-sidebar-nav-main-menu"]/a[contains(@href, :href)]', [
        ':href' => $href,
      ]);
    $link = $this->getSession()->getPage()->findAll('xpath', $xpath);
    $message = strtr('Link containing href %href found.', [
      '%href' => $href,
    ]);
    $this->assertSession()->assert(!empty($link), $message);
  }

  /**
   * Tests that the test content has been created.
   *
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  public function testExistenceOfTestContent() {
    $this->drupalGet('/node/3');
    $this->assertSession()->statusCodeEquals(200);
  }

  /**
   * Tests that the sidebar block is visible.
   *
   * @throws \Behat\Mink\Exception\ElementHtmlException
   * @throws \Behat\Mink\Exception\ElementNotFoundException
   */
  public function testExistenceOfMenuBlock(): void {
    $this->drupalGet('/node/3');
    $this->assertSession()->elementContains('css', '#block-sidebar-nav-main', 'Menu item without children');
  }

  /**
   * Tests first level menu block title link.
   *
   * Tests that viewing a node that is a parent of menu item shows the parent
   * as a link as the title of the menu block.
   *
   * @throws \Behat\Mink\Exception\ElementHtmlException
   * @throws \Behat\Mink\Exception\ElementNotFoundException
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  public function testFirstLevel(): void {
    $path = '/node/1';
    $href = '/node/1';
    $page_title = 'Test page title for top level nav';
    $this->assertMenuBlockTitle($path, $page_title, $href);
  }

  /**
   * Tests second level menu block title link.
   *
   * Tests that viewing a node that is a child of menu item shows the parent
   * as a link as the title of the menu block.
   *
   * @throws \Behat\Mink\Exception\ElementHtmlException
   * @throws \Behat\Mink\Exception\ElementNotFoundException
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  public function testSecondLevel(): void {
    $path = '/node/2';
    $href = '/node/1';
    $page_title = 'Test page title for top level nav';
    $this->assertMenuBlockTitle($path, $page_title, $href);
  }

  /**
   * Tests third level menu block title link.
   *
   * Tests that viewing a node in the 3rd level of depth shows the top-level
   * parent as a link as the title of the menu block.
   *
   * @throws \Behat\Mink\Exception\ElementHtmlException
   * @throws \Behat\Mink\Exception\ElementNotFoundException
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  public function testThirdLevel(): void {
    $path = '/node/7';
    $href = '/node/5';
    $page_title = 'Test three levels';
    $this->assertMenuBlockTitle($path, $page_title, $href);
  }

}
