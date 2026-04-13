<?php

namespace General;

use General\File\FileAnalyzer;
use General\File\FileHandler;

class AppRepository
{
    private static FileAnalyzer $analyzer;
    private static FileHandler  $handler;

    public static function init( FileAnalyzer $analyzer, FileHandler $handler ): void
    {
        self::$analyzer = $analyzer;
        self::$handler  = $handler;
    }

    public static function getAnalyzer(): FileAnalyzer
    {
        return self::$analyzer;
    }

    public static function getHandler(): FileHandler
    {
        return self::$handler;
    }
}
