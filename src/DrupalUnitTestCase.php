<?php

namespace Upal;


class DrupalUnitTestCase extends DrupalTestCase {

  /**
   * Do a full bootstrap of the site.
   */
  function setUp() {
    DrupalBootstrap::bootstrap();
  }

}
