<?php

namespace Upal;


class DrupalIntegratedWebTestCase extends DrupalWebTestCase {
  public function setUp() {
    DrupalBootstrap::backupDatabase();
    parent::setUp();
  }

  public function tearDown() {
    parent::tearDown();
    DrupalBootstrap::restoreDatabase();
  }
}