<?php

require_once dirname(__FILE__) . '/../vendor/autoload.php';

$config = new Upal\Config();
$config->set('drupal_root', getenv('UPAL_ROOT'));
$config->set('web_url', getenv('UPAL_WEB_URL'));
$bootstrap = new Upal\Bootstrap($config);
$bootstrap->setUp();
