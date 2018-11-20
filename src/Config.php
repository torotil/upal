<?php

namespace Upal;


use Noodlehaus\Config as ParentConfig;
use Noodlehaus\AbstractConfig;
use Noodlehaus\Exception\EmptyDirectoryException;

/**
 * Upal configuration.
 */
class Config extends ParentConfig {
  /**
   * Loads a supported configuration file format.
   *
   * @param  string|array|null $path
   *   If empty then it does not load an object
   *
   * @throws EmptyDirectoryException    If `$path` is an empty directory
   */
  public function __construct($path = NULL) {
    if (!empty($path)) {
      parent::__construct($path);
    }
    else {
      AbstractConfig::__construct([]);
    }
  }

  /**
   * Override this method in your own subclass to provide an array of default
   * options and values
   *
   * @return array
   *
   * @codeCoverageIgnore
   */
  protected function getDefaults() {
    return [
      'drush' => getenv('UNISH_DRUSH') ?: getenv('UPAL_DRUSH') ?: trim(`which drush`),
      'web_url' => getenv('UPAL_WEB_URL') ?: 'http://upal',
      'tmp' => getenv('UPAL_TMP') ?: sys_get_temp_dir(),
      'drupal_root' => getenv('UPAL_ROOT') ?: realpath('.'),
    ];
  }

}
