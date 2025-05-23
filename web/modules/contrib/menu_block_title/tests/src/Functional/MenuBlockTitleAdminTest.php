<?php

namespace Drupal\Tests\menu_block_title\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Confirm settings are working for an admin.
 *
 * @package Drupal\Tests\menu_block_title\Functional
 *
 * @group menu_block_title
 */
class MenuBlockTitleAdminTest extends BrowserTestBase {

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
    'administer blocks',
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
   * Admin page test.
   *
   * Tests that a user with the correct permissions can access the
   * block settings.
   *
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  public function testAccessAdminPage(): void {
    $this->drupalGet('/admin/structure/block/manage/sidebar_nav_main');
    $this->assertSession()->statusCodeEquals(200);
  }

  /**
   * Tests that the checkbox is visible on the menu block.
   *
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  public function testMenuBlockSettingsForm(): void {
    $this->drupalGet('/admin/structure/block/manage/sidebar_nav_main');
    $this->assertSession()->checkboxChecked('edit-third-party-settings-menu-block-title-modify-title');
  }

  /**
   * Tests that disabling the checkbox and saving the form works.
   *
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  public function testMenuBlockToggleSettingsForm(): void {
    $this->drupalGet('/admin/structure/block/manage/sidebar_nav_main');
    $this->assertSession()->checkboxChecked('edit-third-party-settings-menu-block-title-modify-title');
    $this->getSession()->getPage()->uncheckField('edit-third-party-settings-menu-block-title-modify-title');
    $this->getSession()->getPage()->pressButton('Save block');
    $this->drupalGet('/admin/structure/block/manage/sidebar_nav_main');
    $this->assertSession()->checkboxNotChecked('edit-third-party-settings-menu-block-title-modify-title');
  }

}
