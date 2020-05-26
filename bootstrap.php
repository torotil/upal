<?php

namespace Upal;

// Include our autoloader in case PHPUnit wasn’t called from the same
// composer environment.
@include_once(__DIR__ . '/../../autoload.php');
// Include autoload in dev builds.
@include_once(__DIR__ . '/vendor/autoload.php');

// Define class aliases for backwards compatibility.
class_alias(DrupalUnitTestCase::class, 'DrupalUnitTestCase');
class_alias(DrupalWebTestCase::class, 'DrupalWebTestCase');

// Codecoverage includes all files it can find before any test is executed.
// This means autoloading needs to already work at that stages. Unless we
// assume that autoloading works even for modules that haven’t been loaded
// yet we need a full bootstrap.
DrupalBootstrap::bootstrap(7);
