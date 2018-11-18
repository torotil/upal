A group of classes used to help run tests using PHPUnit in Drupal.

upal assumes that tests are allowed to modify the database.

## Usage

- Install PHPUnit (https://phpunit.de/manual/current/en/installation.html) and Drush (http://drupal.org/project/drush).
- Checkout or download a core Drupal that is to be tested (only tested with 7.x).
- Implement Upal\Bootstrap class

## Configuration

Configuration can be loaded from array or file (YAML, XML, etc).  Uses this config library: https://github.com/hassankhan/config.

### Example:

```php
$config = new Upal\Config();
$config->set('drush', '/path/to/drush');
$config->set('drupal_root', '/path/to/drupal/root');

$bootstrap = new Upal\Bootstrap($config);
$bootstrap->setUp();
```

### Config Defaults

* drush => trim(`which drush`)
* root => realpath('.')
* web_url => 'http://upal'
* tmp => sys_get_temp_dir()
* drupal_root => realpath('.')

