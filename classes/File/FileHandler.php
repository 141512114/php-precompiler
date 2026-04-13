<?php

namespace General\File;

use General\File\Include\FileInclude;
use RuntimeException;

/**
 * Handles file operations, including resolving paths, reading files, and sanitizing content.
 */
class FileHandler
{
    /**
     * Resolves a path to its absolute form.
     *
     * @param string $path The path to resolve.
     *
     * @return string|null The absolute path, or null if not found.
     */
    public function resolvePath( string $path ): ?string
    {
        $realPath = realpath( $path );

        return ( $realPath !== FALSE && is_file( $realPath ) ) ? $realPath : NULL;
    }

    /**
     * Resolves a relative include path to an absolute path.
     *
     * @param FileInclude $fileInclude The include to resolve.
     * @param string|null $basePath    The base path for relative resolution.
     *
     * @return string|null The resolved absolute path, or null if not found.
     */
    public function resolveIncludePath( FileInclude $fileInclude, ?string $basePath = NULL ): ?string
    {
        $includePath = $fileInclude->getPath();

        if ( $includePath === NULL ) {
            throw new RuntimeException( 'FileInclude has no path set.' );
        }

        // Wenn __DIR__ verwendet wird, ist der Pfad relativ zum basePath,
        // auch wenn er mit "/" beginnt
        if ( !empty( $fileInclude->getDirConstant() ) && $basePath !== NULL ) {

            // Führenden Slash entfernen, da basePath bereits das Verzeichnis ist
            $includePath = ltrim( $includePath, '/' );
            $resolved    = $this->resolvePath( $basePath . DIRECTORY_SEPARATOR . $includePath );
            if ( $resolved !== NULL ) return $resolved;

        }

        // Echter absoluter Pfad (ohne __DIR__)
        if ( $this->isAbsolutePath( $includePath ) ) return $this->resolvePath( $includePath );

        // Relativen Pfad auflösen
        if ( $basePath !== NULL ) {

            $resolved = $this->resolvePath( $basePath . DIRECTORY_SEPARATOR . $includePath );
            if ( $resolved !== NULL ) return $resolved;

        }

        // Fallback: include_path durchsuchen
        $resolvedPath = stream_resolve_include_path( $includePath );

        return ( $resolvedPath !== FALSE ) ? $resolvedPath : NULL;
    }

    /**
     * Checks if a path is absolute.
     *
     * @param string $path The path to check.
     *
     * @return bool True if the path is absolute.
     */
    public function isAbsolutePath( string $path ): bool
    {
        // Windows absolute path (z.B. C:\)
        if ( preg_match( '/^[A-Z]:/i', $path ) === 1 ) {
            return TRUE;
        }

        // Unix: Nur absolute wenn / UND die Datei existiert
        // (unterscheidet echte absolute Pfade von __DIR__ . '/..')
        if ( str_starts_with( $path, '/' ) && is_file( $path ) ) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Get the contents of a file.
     *
     * @param string $filePath The path to the file.
     *
     * @return string|false The contents of the file or false on failure.
     */
    public function getFileContents( string $filePath ): string|false
    {
        if ( is_file( $filePath ) ) {
            return file_get_contents( $filePath );
        }
        return FALSE;
    }
}
