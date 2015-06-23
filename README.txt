This is a fork of the (now discontinued) upal project for Drupal 7.

The main goal is to provide a test framwork that …

- … is *fast enough* to do test driven development for drupal modules.
- … can be used to *test basic business use-cases in staging environments* (ie. live site clones).
- … is compatible with *popular CI-tools* like Jenkins and TravisCI.

upal assumes that tests are allowed to modify the database.

Usage
--------
- Install PHPUnit (https://phpunit.de/manual/current/en/installation.html) and Drush (http://drupal.org/project/drush).
- Checkout or download a core Drupal that is to be tested (only tested with 7.x).
  -- Map http://upal to this Drupal in your web server config. If not possible,
     configure UPAL_WEB_URL in phpunit.xml (see Notes).
  -- Create an 'upal' database on your database server.
  -- If your db_url is not mysql://root:@127.0.0.1/upal, configure UPAL_DB_URL in
     phpunit.xml (see Notes).
- From the drupal root directory that is to be tested, run lines like:
    `phpunit --configuration /path/to/upal/phpunit.xml FilterUnitTestCase core/modules/filter/filter.test`
    `phpunit --debug --configuration /path/to/upal/phpunit.xml core/modules/book/book.test`

Notes
----------
- If customization is needed as per above, Copy phpunit.xml.dist to phpunit.xml and edit.
