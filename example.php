<?php

require_once('Kaboom.php');

use Beryllium\Kaboom\Kaboom;

$kaboom = new Kaboom('dev');
echo "Everything is OK.\n";
$kaboom = new Kaboom('prod');
