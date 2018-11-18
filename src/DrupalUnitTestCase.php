<?php

namespace Upal;


class DrupalUnitTestCase extends DrupalTestCase {

  /**
   * Do a full bootstrap of the site.
   */
  function setUp() {
    drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
  }

}
