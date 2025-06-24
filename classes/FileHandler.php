<?php

namespace General;

class FileHandler
{
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

    /**
     * Sanitize file contents to prevent unwanted layout crashes.
     *
     * @param string $contents The contents of the file.
     *
     * @return string Sanitized contents.
     */
    public function sanitizeContents( string $contents ): string
    {
        return nl2br( htmlspecialchars( $contents ) );
    }
}