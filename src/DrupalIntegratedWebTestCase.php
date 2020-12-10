<?php

namespace Upal;


class DrupalIntegratedWebTestCase extends DrupalWebTestCase {

  public function setUp() : void {
    DrupalBootstrap::backupDatabase();
    parent::setUp();
  }

  public function tearDown() : void {
    parent::tearDown();
    DrupalBootstrap::restoreDatabase();
  }

}
