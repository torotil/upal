<?php

namespace Upal;

use PHPUnit\Framework\Error\Warning;

/**
 * Some simple tests for whether the boostrap was successful.
 */
class BootstrapTest extends DrupalUnitTestCase {

  /**
   * Test that the default site was bootstrapped.
   */
  public function testConfPath() {
    $this->assertEquals('sites/default', conf_path());
  }

  /**
   * Test that PHP warnings lead to failing tests.
   */
  public function testWarning() {
    $this->expectException(Warning::class);
    trigger_error('Test warning', E_USER_WARNING);
  }

  /**
   * Test include path.
   *
   * Some rely on DRUPAL_ROOT being in the include_path.
   */
  public function testIncludePath() {
    include_once drupal_get_path('module', 'block') . '/block.admin.inc';
    $this->assertTrue(function_exists('block_admin_demo'));
  }

  /**
   * Test that the current working directory is DRUPAL_ROOT.
   *
   * Some modules seems to expect this (ie. libraries).
   */
  public function testCwd() {
    $this->assertEquals(DRUPAL_ROOT, getcwd());
  }

  /**
   * Test that module_hook_info() works as expected.
   */
  public function testModuleHookInfo() {
    $info = module_hook_info();
    // It should include info defined in system_hook_info().
    $this->assertEqual(['group' => 'tokens'], $info['token_info']);
  }

}
