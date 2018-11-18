<?php

namespace Upal;

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

}
