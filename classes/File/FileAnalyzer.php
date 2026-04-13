<?php

namespace General\File;

use General\File\Include\FileInclude;

/**
 * Analyzes PHP files for include/require statements and replaces them with their content.
 */
class FileAnalyzer
{
    private FileHandler $handler;

    public function __construct( FileHandler $handler )
    {
        $this->handler = $handler;
    }

    /**
     * Finds all include/require statements in a given PHP file with their positions.
     *
     * @param string $file The path to the PHP file to analyze.
     *
     * @return FileInclude[] An array of include/require information found in the file.
     */
    public function findIncludes( string $file ): array
    {
        $includes     = [];
        $fileContents = file_get_contents( $file );

        if ( $fileContents === FALSE ) return $includes;

        // Regex: matches include/require (optionally _once), optional parentheses,
        // captures the type, quote character, and file path.
        $pattern = '/\b((?:include|require)(?:_once)?)\s*\(?\s*(__DIR__|__FILE__|dirname\s*\([^)]+\))?\s*\.?\s*([\'\"])([^\'"]+)\3\s*\)?\s*;/i';

        if ( preg_match_all( $pattern, $fileContents, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE ) === FALSE ) {
            return $includes;
        }

        foreach ( $matches as $match ) {

            $fullMatch   = $match[ 0 ][ 0 ];
            $startPos    = intval( $match[ 0 ][ 1 ] );
            $type        = strtolower( $match[ 1 ][ 0 ] );                       // include, require, include_once, require_once
            $dirConstant = !empty( $match[ 2 ][ 0 ] ) ? $match[ 2 ][ 0 ] : NULL; // __DIR__, __FILE__, etc.
            $path        = isset( $match[ 4 ][ 0 ] ) ? trim( $match[ 4 ][ 0 ] ) : '';

            if ( empty( $path ) ) continue;

            // Berechne die Zeilennummer
            $lineNumber = substr_count( substr( $fileContents, 0, $startPos ), "\n" ) + 1;

            $fileInclude = new FileInclude( $type, $path );
            $fileInclude->setFullMatch( $fullMatch )
                        ->setDirConstant( $dirConstant )
                        ->setStart( $startPos )
                        ->setEnd( $startPos + strlen( $fullMatch ) )
                        ->setLineNumber( $lineNumber );

            $includes[] = $fileInclude;

        }

        return $includes;
    }

    /**
     * Replaces include/require statements with the content of the included files.
     *
     * @param File        $file     The File object representing the PHP file to process.
     * @param string|null $basePath Optional base path for resolving relative includes.
     *
     * @return string|false The modified file content, or false on failure.
     */
    public function replaceIncludesWithContent( File $file, ?string $basePath = NULL ): string|false
    {
        $path = $file->getPath();

        $fileContents = file_get_contents( $path );

        if ( $fileContents === FALSE ) return FALSE;

        $basePath = $basePath ?? dirname( $path );
        $includes = $this->findIncludes( $path );

        // Von hinten nach vorne ersetzen, um Positionsverschiebungen zu vermeiden
        $includes = array_reverse( $includes );

        foreach ( $includes as $include ) {

            $includePath = $this->handler->resolveIncludePath( $include, $basePath );

            if ( $includePath === NULL || !is_readable( $includePath ) ) continue;

            $includeContent = file_get_contents( $includePath );

            if ( $includeContent === FALSE ) continue;

            // PHP-Tags aus dem einzufügenden Inhalt entfernen
            $includeContent = $this->handler->stripPhpTags( $includeContent );

            // Ersetze das Include-Statement mit dem Dateiinhalt
            $fileContents = substr_replace(
                $fileContents,
                $includeContent,
                $include->getStart(),
                $include->getEnd() - $include->getStart()
            );

        }

        // Abschließend: Mehrfache Leerzeilen im gesamten Dokument reduzieren
        return preg_replace( '/\n{3,}/', "\n\n", $fileContents );
    }
}
