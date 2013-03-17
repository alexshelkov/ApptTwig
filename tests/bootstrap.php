<?php
$applicationRoot = __DIR__ . '/../';

chdir($applicationRoot);

exec('rm -Rf cache');
exec('mkdir -p cache/twig/');
exec('chmod -R a+w cache');

define('TEST_APPLICATION_CONFIG', __DIR__ . '/test.application.config.php');
define('TEST_STUB', __DIR__ . '/ApptTwigStub');

// Init composer autoloaders
$loader = require_once __DIR__ . '/../vendor/autoload.php';