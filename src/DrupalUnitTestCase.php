<?php

namespace Upal;


class DrupalUnitTestCase extends DrupalTestCase {
  function setUp() {
    DrupalBootstrap::bootstrap();
  }
}