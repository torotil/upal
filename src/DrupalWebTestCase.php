<?php
/**
 * Created by PhpStorm.
 * User: indytechcook
 * Date: 8/11/15
 * Time: 10:51 PM
 */

namespace Upal;


class DrupalWebTestCase extends DrupalTestCase {
  protected $backupGlobals = FALSE;
  public function setUp() {
    DrupalBootstrap::bootstrap();

    // Use the test mail class instead of the default mail handler class.
    variable_set('mail_system', array('default-system' => 'TestingMailSystem'));
  }
}