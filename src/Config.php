<?php

namespace Upal;


use Noodlehaus\Config as ParentConfig;
use Noodlehaus\Exception\EmptyDirectoryException;

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
      'drush' => trim(`which drush`),
      'db_url' => 'mysql://root:@127.0.0.1/upal',
      'web_url' => 'http://upal',
      'tmp' => sys_get_temp_dir(),
      'drupal_root' => realpath('.'),
    ];
  }


}