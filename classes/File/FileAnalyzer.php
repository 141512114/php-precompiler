<?php

namespace General\File;

use General\File\Include\FileInclude;

class FileAnalyzer
{
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
            $path = isset( $match[ 4 ][ 0 ] ) ? trim( $match[ 4 ][ 0 ] ) : '';

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
     * @param string      $file     The path to the PHP file to process.
     * @param string|null $basePath Optional base path for resolving relative includes.
     *
     * @return string|false The modified file content, or false on failure.
     */
    public function replaceIncludesWithContent( string $file, ?string $basePath = NULL ): string|false
    {
        $fileContents = file_get_contents( $file );

        if ( $fileContents === FALSE ) return FALSE;

        $basePath = $basePath ?? dirname( $file );
        $includes = $this->findIncludes( $file );

        // Von hinten nach vorne ersetzen, um Positionsverschiebungen zu vermeiden
        $includes = array_reverse( $includes );

        foreach ( $includes as $include ) {

            $includePath = $this->resolveIncludePath( $include[ 'path' ], $basePath );

            if ( $includePath === NULL || !is_readable( $includePath ) ) continue;

            $includeContent = file_get_contents( $includePath );

            if ( $includeContent === FALSE ) continue;

            // PHP-Tags aus dem einzufügenden Inhalt entfernen
            $includeContent = $this->stripPhpTags( $includeContent );

            // Ersetze das Include-Statement mit dem Dateiinhalt
            $fileContents = substr_replace(
                $fileContents,
                $includeContent,
                $include[ 'startPos' ],
                $include[ 'endPos' ] - $include[ 'startPos' ]
            );

        }

        return $fileContents;
    }

    /**
     * Resolves a relative include path to an absolute path.
     *
     * @param string $includePath The relative path to resolve.
     * @param string $basePath    The base path to resolve relative paths against.
     *
     * @return string|null The absolute path if found, NULL otherwise.
     */
    private function resolveIncludePath( string $includePath, string $basePath ): ?string
    {
        // Wenn der Pfad bereits absolut ist
        if ( str_starts_with( $includePath, '/' ) || preg_match( '/^[A-Z]:/i', $includePath ) ) {
            return is_file( $includePath ) ? $includePath : NULL;
        }

        // Relativen Pfad auflösen
        $resolvedPath = realpath( $basePath . DIRECTORY_SEPARATOR . $includePath );

        return ( $resolvedPath !== FALSE && is_file( $resolvedPath ) ) ? $resolvedPath : NULL;
    }

    /**
     * Strips opening and closing PHP tags from content.
     *
     * @param string $content The content to strip PHP tags from.
     *
     * @return string The content with PHP tags stripped.
     */
    private function stripPhpTags( string $content ): string
    {
        // Entferne öffnende PHP-Tags (<?php, <?=, <?)
        $content = preg_replace( '/^<\?(?:php)?\s*/i', '', $content );

        // Entferne schließende PHP-Tags am Ende
        return preg_replace( '/\s*\?>\s*$/', '', $content );
    }
}
