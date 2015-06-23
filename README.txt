'Assume Clone Site' Drupal's test suite based on PHPUnit (http://www.phpunit.de/).


a.) SUT 'System Under Test' concept is a direct obstacle to fast iteration; The idea of creating a pseudo database schema and
pseudo site install through prefixing does not scale. This concept caters to Drupal Mom and Pop sites that dont have the resources of a 
functioning (dev|test)->staging->prod infrastructure.

b.) Test Suite is ACS 'Assume Clone Site'. We assume this test suite will be executed on a cloned site already.

Site example.dev is cloned as site example.test, and the test suite is triggered at the cloned example.test site.

- Hence we have no worries about creating demo content, database objects.
- Unlike the SUT Drupal Test Suite that has to do expensive and time consuming preparing of variables like (enabled modules, enabled features)
because we went with ACS. We dont have to waste valuable stakeholder time.

c.) Primary Use Case is D7, its my day to day work.

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
