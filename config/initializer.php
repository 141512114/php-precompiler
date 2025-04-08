<?php

require_once __DIR__ . '/../vendor/autoload.php';

ob_start();

define('TEST', "Hello World out of a define");
const TEST2 = "Hello World out of a constant";
$_TEST = "Hello World out of a normal variable";
