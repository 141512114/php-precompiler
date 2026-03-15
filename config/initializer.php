<?php

use General\FileAnalyzer;
use General\FileHandler;
use General\FileRepository;

ob_start();

$_FILEHANDLER  = new FileHandler();
$_FILEANALYZER = new FileAnalyzer();

$_FILEREPOSITORY = new FileRepository($_FILEHANDLER, $_FILEANALYZER);
