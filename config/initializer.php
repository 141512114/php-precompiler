<?php

use General\File\FileRepository;

// Fehlerprotokollierung aktivieren
ini_set( 'log_errors', '1' );
ini_set( 'error_log', '/logs/php_error.log' );
error_reporting( E_ALL );

ob_start();

$_CONTAINER = require_once( __DIR__ . '/container.php' );

/** @var FileRepository $_FILEREPOSITORY */
$_FILEREPOSITORY = $_CONTAINER->get( FileRepository::class );
