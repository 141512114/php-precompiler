<?php

use General\FileAnalyzer;
use General\FileHandler;

session_start();
ob_start();

$_FILEANALYZER = new FileAnalyzer();
$_FILEHANDLER  = new FileHandler();

$_SESSION[ 'is_precompiled' ] = FALSE;
