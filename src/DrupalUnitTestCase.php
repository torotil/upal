<?php

namespace Upal;


class DrupalUnitTestCase extends DrupalTestCase {

  /**
   * Do a full bootstrap of the site.
   */
  public function setUp() : void {
    DrupalBootstrap::bootstrap();
    $this->oldCwd = getcwd();
    chdir(DRUPAL_ROOT);
  }

  /**
   * Reset the current working directory.
   */
  public function tearDown() : void {
    chdir($this->oldCwd);
  }

}
