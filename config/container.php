<?php

use General\Common\Container;
use General\File\FileAnalyzer;
use General\File\FileFactory;
use General\File\FileHandler;
use General\File\FileRepository;
use General\File\FileSanitizer;

$container = new Container();

########################################################################
## Basis-Services registrieren
########################################################################

$container->set( FileSanitizer::class, fn() => new FileSanitizer() );

$container->set( FileHandler::class, fn() => new FileHandler() );

$container->set( FileAnalyzer::class, fn( Container $c ) => new FileAnalyzer(
    $c->get( FileHandler::class ),
    $c->get( FileSanitizer::class )
) );

$container->set( FileFactory::class, fn( Container $c ) => new FileFactory(
    $c->get( FileHandler::class ),
    $c->get( FileAnalyzer::class ),
    $c->get( FileSanitizer::class )
) );

$container->set( FileRepository::class, fn( Container $c ) => new FileRepository(
    $c->get( FileHandler::class ),
    $c->get( FileAnalyzer::class ),
    $c->get( FileSanitizer::class ),
    $c->get( FileFactory::class )
) );

return $container;
