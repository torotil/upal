A group of classes used to help run tests using PHPUnit in Drupal.

upal assumes that tests are allowed to modify the database.

## Usage

- Install PHPUnit (https://phpunit.de/manual/current/en/installation.html) and Drush (http://drupal.org/project/drush).
- Checkout or download a core Drupal that is to be tested (only tested with 7.x).
- Use any of `Upal\*TestCase` classes as base-class for your tests.
- With PHP7.2+ you need a bootstrap script that bootstraps at least to level 4, ie. calls `\Upal\DrupalBootstrap::bootstrap(4)`.

## Configuration

By default the configuration is read from `UPAL_*` environment variables. If that’s
fine for you only need to ensure that upal’s classes are autoloadable.

Configuration can be loaded from array or file (YAML, XML, etc).
upal uses this config library: https://github.com/hassankhan/config.

### Example bootstrap.php

```php
$config = new Upal\Config();
$config->set('drush', '/path/to/drush');
$config->set('drupal_root', '/path/to/drupal/root');
Upal\DrupalBootstrap::$config = $config;
```

### Config Defaults

* `drush`: `UPAL_DRUSH`, `trim(`which drush`)`
* `root`: `UPAL_ROOT`, `realpath('.')`
* `web_url`: `UPAL_WEB_URL`, `'http://upal'`
* `tmp`: `UPAL_TMP`, `sys_get_temp_dir()`

