<?php

namespace General;

class FileAnalyzer
{
    /**
     * Retrieves the name of the currently executing PHP file.
     *
     * @return array|string The basename of the current file as a string, or an array if additional path info is requested.
     */
    public function getCurrentFile(): array|string
    {
        return pathinfo( $_SERVER[ 'PHP_SELF' ], PATHINFO_BASENAME );
    }

    /**
     * Finds all include/require statements in a given PHP file.
     *
     * @param string $file The path to the PHP file to analyze.
     *
     * @return string[] An array of include/require paths found in the file.
     */
    public function findIncludes( string $file ): array
    {
        $includes     = [];
        // @TODO: Instead of looking for includes in the current file, it might be more efficient to use `get_included_files()` to get all included files
        $fileContents = file_get_contents( $file );
        // @TODO: Then it is possible to combine the files with the current file and remove their implementations from the code (anywhere), so that the current file only contains the includes and no other code

        if ( $fileContents === FALSE ) {
            return $includes; // Return empty array if file cannot be read
        }

        // Use regex to find all include/require statements
        // @TODO: Consider using a more robust parser for complex cases => this regex is basic and may not cover all edge cases.
        preg_match_all( '/\b(?<!\$)(?:include|require)(_once)?\s*\(?\s*(.+?)\s*\)?\s*;/', $fileContents, $matches );

        if ( !empty( $matches[ 2 ] ) ) {
            foreach ( $matches[ 2 ] as $include ) {
                $includes[] = trim( $include );
            }
        }

        return $includes;
    }
}