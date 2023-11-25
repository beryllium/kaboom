<?php

require __DIR__ . '/vendor/autoload.php';

use Beryllium\Kaboom\Kaboom;

$env = 'dev';

$kaboom = new Kaboom();
$kaboom->condition(
    fn () => $env === 'prod',
    fn () => 'This should never run in prod mode!'
);

echo "\n\nEverything is OK in Dev mode. Switching to Prod mode.\n\n\n";

$env = 'prod';
$kaboom->condition(
    fn () => $env === 'prod',
    fn () => 'This should never run in prod mode!'
);

