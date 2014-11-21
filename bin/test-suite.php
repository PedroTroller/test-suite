<?php

include __DIR__ . '/../vendor/autoload.php';

$input = new Symfony\Component\Console\Input\ArgvInput($argv);
$application = new Knp\Async\Application;
$application->run($input);
