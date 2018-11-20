<?php

namespace Upal;

class DrupalBootstrap {
  static $database_dump;
  static $has_run;

  public static $config = NULL;

  public static function initializeDrupal() {
    if (self::$has_run) {
      return;
    }
    self::$has_run = TRUE;
    $config = self::$config ?? new Config();

    // Set the env vars that Drupal expects. Largely copied from drush.
    $url = parse_url($config->get('web_url'));

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
    if ($_SERVER['SERVER_PORT']) {
      $_SERVER['HTTP_HOST'] .= ':' . $_SERVER['SERVER_PORT'];
    }

    define('UNISH_DRUSH', $config->get('drush'));
    define('UPAL_WEB_URL', $config->get('web_url'));
    define('UPAL_ROOT', $config->get('drupal_root'));
    define('UPAL_TMP', $config->get('tmp'));

    define('DRUPAL_ROOT', UPAL_ROOT);
    require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
    // Some modules rely on this.
    set_include_path(DRUPAL_ROOT . PATH_SEPARATOR . get_include_path());
    DrupalBootstrap::bootstrap(DRUPAL_BOOTSTRAP_CONFIGURATION);
    restore_error_handler();
    restore_exception_handler();
  }

  public static function bootstrap($phase = 7) {
    self::initializeDrupal();
    $old_dir = getcwd();
    chdir(UPAL_ROOT);
    drupal_bootstrap($phase);
    chdir($old_dir);
  }

  public static function backupDatabase() {
    self::$database_dump = self::directory_cache('db_dumps') . '/' .
      basename(conf_path()) . '-' . REQUEST_TIME . '.sql';

    if (!file_exists(self::$database_dump)) {
      $cmd = sprintf('%s sql-dump --uri=%s --root=%s --result-file=%s',
        UNISH_DRUSH, UPAL_WEB_URL, UPAL_ROOT, self::$database_dump);
      exec($cmd, $output, $return);
      if ($return) {
        echo "Failed to create database backup.\n";
        echo $output;
        exit(1);
      }
    }
  }

  public static function restoreDatabase() {
    $cmd = sprintf('`%s sql-connect --uri=%s --root=%s` < %s',
      UNISH_DRUSH, UPAL_WEB_URL, UPAL_ROOT, self::$database_dump);
    exec($cmd, $output, $return);
    if ($return) {
      echo "Failed to restore the database backup.\n";
      echo $output;
      exit(1);
    }

  }

  public static function directory_cache($subdir = '') {
    $dir = UPAL_TMP . '/' . $subdir;
    if (!file_exists($dir)) {
      drupal_mkdir($dir, NULL, TRUE);
    }
    return $dir;
  }
}
