<?php

use General\AppRepository;
use General\File\FileAnalyzer;
use General\File\FileHandler;
use General\File\FileRepository;

// Fehlerprotokollierung aktivieren
ini_set( 'log_errors', '1' );
ini_set( 'error_log', '/logs/php_error.log' );
error_reporting( E_ALL );

ob_start();

$_FILEHANDLER  = new FileHandler();
$_FILEANALYZER = new FileAnalyzer( $_FILEHANDLER );

$_APPREPOSITORY = new AppRepository( $_FILEANALYZER, $_FILEHANDLER );

$_FILEREPOSITORY = new FileRepository( $_FILEHANDLER, $_FILEANALYZER );
