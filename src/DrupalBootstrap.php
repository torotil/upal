<?php

namespace Upal;


class DrupalBootstrap {
  static $database_dump;

  public static function bootstrap($phase = 7) {
    $old_dir = getcwd();
    chdir(UPAL_ROOT);
    drupal_bootstrap($phase);
    restore_error_handler();
    restore_exception_handler();
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
