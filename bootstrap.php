<?php

namespace Upal;

// Include our autoloader in case PHPUnit wasn’t called from the same
// composer environment.
@include_once(__DIR__ . '/../../autoload.php');

// Define class aliases for backwards compatibility.
class_alias(DrupalUnitTestCase::class, 'DrupalUnitTestCase');
class_alias(DrupalWebTestCase::class, 'DrupalWebTestCase');

// Some tests use module_load_include() in global scope.
DrupalBootstrap::bootstrap(3);
