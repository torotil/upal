<?php

namespace Upal;

use PHPUnit\Framework\TestCase;

/**
 * Test configuration management.
 */
class ConfigTest extends TestCase {

  /**
   * Test reading config defaults from enviroment variables.
   */
  public function testReadingDefaultsFromEnv() {
    putenv('UPAL_DRUSH=mydrush');
    putenv('UPAL_WEB_URL=http://myupal');
    putenv('UPAL_TMP=/mytemp');
    putenv('UPAL_ROOT=/myroot');
    $config = new Config();
    $this->assertEquals('mydrush', $config->get('drush'));
    $this->assertEquals('http://myupal', $config->get('web_url'));
    $this->assertEquals('/mytemp', $config->get('tmp'));
    $this->assertEquals('/myroot', $config->get('drupal_root'));
  }

}
