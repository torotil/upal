<?php

namespace Upal;


class DrupalWebTestCase extends DrupalTestCase {
  protected $backupGlobals = FALSE;
  public function setUp() : void {
    DrupalBootstrap::bootstrap();

    // Use the test mail class instead of the default mail handler class.
    variable_set('mail_system', array('default-system' => 'TestingMailSystem'));
  }
}
