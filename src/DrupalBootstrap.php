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
    static::drupalBootstrap($phase);
    chdir($old_dir);
  }

  /**
   * Go through Drupal’s bootstrap phases.
   *
   * This is a slightly modified copy of drupal_bootstrap().
   */
  protected static function drupalBootstrap($phase = NULL, $new_phase = TRUE) {
    // Not drupal_static(), because does not depend on any run-time information.
    static $phases = array(
      DRUPAL_BOOTSTRAP_CONFIGURATION,
      DRUPAL_BOOTSTRAP_PAGE_CACHE,
      DRUPAL_BOOTSTRAP_DATABASE,
      DRUPAL_BOOTSTRAP_VARIABLES,
      DRUPAL_BOOTSTRAP_SESSION,
      DRUPAL_BOOTSTRAP_PAGE_HEADER,
      DRUPAL_BOOTSTRAP_LANGUAGE,
      DRUPAL_BOOTSTRAP_FULL,
    );
    // Not drupal_static(), because the only legitimate API to control this is to
    // call drupal_bootstrap() with a new phase parameter.
    static $final_phase;
    // Not drupal_static(), because it's impossible to roll back to an earlier
    // bootstrap state.
    static $stored_phase = -1;

    if (isset($phase)) {
      // When not recursing, store the phase name so it's not forgotten while
      // recursing but take care of not going backwards.
      if ($new_phase && $phase >= $stored_phase) {
        $final_phase = $phase;
      }

      // Call a phase if it has not been called before and is below the requested
      // phase.
      while ($phases && $phase > $stored_phase && $final_phase > $stored_phase) {
        $current_phase = array_shift($phases);

        // This function is re-entrant. Only update the completed phase when the
        // current call actually resulted in a progress in the bootstrap process.
        if ($current_phase > $stored_phase) {
          $stored_phase = $current_phase;
        }

        switch ($current_phase) {
          case DRUPAL_BOOTSTRAP_CONFIGURATION:
            require_once DRUPAL_ROOT . '/includes/request-sanitizer.inc';
            _drupal_bootstrap_configuration();
            break;

          case DRUPAL_BOOTSTRAP_PAGE_CACHE:
            _drupal_bootstrap_page_cache();
            break;

          case DRUPAL_BOOTSTRAP_DATABASE:
            _drupal_bootstrap_database();
            break;

          case DRUPAL_BOOTSTRAP_VARIABLES:
            _drupal_bootstrap_variables();
            break;

          case DRUPAL_BOOTSTRAP_SESSION:
            require_once DRUPAL_ROOT . '/' . variable_get('session_inc', 'includes/session.inc');
            static::drupalSessionInitialize();
            break;

          case DRUPAL_BOOTSTRAP_PAGE_HEADER:
            _drupal_bootstrap_page_header();
            break;

          case DRUPAL_BOOTSTRAP_LANGUAGE:
            drupal_language_initialize();
            break;

          case DRUPAL_BOOTSTRAP_FULL:
            require_once DRUPAL_ROOT . '/includes/common.inc';
            _drupal_bootstrap_full();
            break;
        }
      }
    }
    return $stored_phase;
  }

  /**
   * Stub version of drupal_session_initialize().
   */
  protected static function drupalSessionInitialize() {
    global $user;
    $GLOBALS['lazy_session'] = TRUE;
    $user = drupal_anonymous_user();
    date_default_timezone_set(drupal_get_user_timezone());
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
