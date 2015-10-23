<?php

namespace Upal;

class Bootstrap {
  static $has_run = FALSE;

  /**
   * @var \Upal\Config
   */
  protected $config;

  /**
   * Construct the object.
   *
   * Keeping it simple with an array.
   *
   * @param Config $config
   */
  public function __construct(Config $config) {
    $this->config = $config;
  }

  public function setUp() {
    if (self::$has_run) {
      return;
    }

    self::$has_run = TRUE;

    // Set the env vars that Drupal expects. Largely copied from drush.
    $url = parse_url($this->config->get('web_url'));

    if (array_key_exists('path', $url)) {
      $_SERVER['PHP_SELF'] = $url['path'] . '/index.php';
    }
    else {
      $_SERVER['PHP_SELF'] = '/index.php';
    }

    $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'] = $_SERVER['PHP_SELF'];
    $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
    $_SERVER['REQUEST_METHOD']  = NULL;

    $_SERVER['SERVER_SOFTWARE'] = NULL;
    $_SERVER['HTTP_USER_AGENT'] = NULL;

    $_SERVER['HTTP_HOST'] = $url['host'];
    $_SERVER['SERVER_PORT'] = array_key_exists('port', $url) ? $url['port'] : NULL;

    define('DRUPAL_ROOT', $this->config->get('drupal_root'));
    define('UNISH_DRUSH', $this->config->get('drush'));
    define('UPAL_WEB_URL', $this->config->get('web_url'));
    define('UPAL_ROOT', $this->config->get('root'));
    define('UPAL_TMP', $this->config->get('tmp'));

    require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
    DrupalBootstrap::bootstrap(DRUPAL_BOOTSTRAP_VARIABLES);
  }
}