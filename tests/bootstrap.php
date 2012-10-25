<?php
$applicationRoot = __DIR__ . '/../';

chdir($applicationRoot);

exec('rm -Rf cache');
exec('mkdir -p cache/twig/');
exec('chmod -R a+w cache');

// Init composer autoloaders
$loader = require_once __DIR__ . '/../vendor/autoload.php';
$loader->add('ApptTwig', __DIR__ . '/../src');