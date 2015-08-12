<?php

namespace Upal;

class Bootstrap {
  public static function init() {
    $define = function($name, $default) {
      define($name, getenv($name)
        ? getenv($name)
        : (!empty($GLOBALS[$name])
          ? $GLOBALS[$name]
          : $default));
    };

    static $has_run = FALSE;
    if ($has_run) { return; }
    $has_run = TRUE;

    $define('UNISH_DRUSH', trim(`which drush`));

    // We read from globals here because env can be empty and ini did not work in quick test.
    $define('UPAL_DB_URL', 'mysql://root:@127.0.0.1/upal');

    // Make sure we use the right Drupal codebase.
    $define('UPAL_ROOT', realpath('.'));

    // The URL that browser based tests should use.
    $define('UPAL_WEB_URL', 'http://upal');

    $define('UPAL_TMP', sys_get_temp_dir());

    // Set the env vars that Drupal expects. Largely copied from drush.
    $url = parse_url(UPAL_WEB_URL);

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

    set_include_path(UPAL_ROOT . PATH_SEPARATOR . get_include_path());

    if (!defined("DRUPAL_ROOT")) {
      define('DRUPAL_ROOT', UPAL_ROOT);
    }
    require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
    DrupalBootstrap::bootstrap(DRUPAL_BOOTSTRAP_VARIABLES);
  }
}