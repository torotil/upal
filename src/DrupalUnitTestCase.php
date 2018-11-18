<?php

namespace Upal;


class DrupalUnitTestCase extends DrupalTestCase {

  function setUp() {
    echo __FUNCTION__ . "\n";
    echo "Starting full bootstrap.\n";
    drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
    echo "Bootstrap full done.\n";
  }

}
