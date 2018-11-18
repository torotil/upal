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

}
