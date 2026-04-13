<?php

namespace General\File;

class FileSanitizer
{
    /**
     * Sanitize file contents to prevent unwanted layout crashes.
     *
     * @param string $contents The contents of the file.
     *
     * @return string Sanitized contents.
     */
    public function sanitizeContents( string $contents ): string
    {
        return nl2br( htmlspecialchars( $contents, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
    }

    /**
     * Strips opening and closing PHP tags from content.
     *
     * @param string $content The content to strip PHP tags from.
     *
     * @return string The content with PHP tags stripped.
     */
    public function stripPhpTags( string $content ): string
    {
        // Entferne öffnende PHP-Tags (<?php, <?=, <?)
        $content = preg_replace( '/^<\?(?:php)?\s*/i', '', $content );

        // Entferne schließende PHP-Tags am Ende
        $content = preg_replace( '/\s*\?>\s*$/', '', $content );

        // Entferne führende/trailing Leerzeilen
        $content = trim( $content );
        // Reduziere mehrfache Leerzeilen auf maximal eine
        return preg_replace( '/\n{3,}/', "\n\n", $content );
    }

    /**
     * Reduces multiple consecutive blank lines to a maximum of one.
     *
     * @param string $content The content to clean.
     * @param int    $maxBlankLines Maximum number of consecutive blank lines (default: 1).
     *
     * @return string The cleaned content.
     */
    public function reduceBlankLines( string $content, int $maxBlankLines = 1 ): string
    {
        $replacement = str_repeat( "\n", $maxBlankLines + 1 );
        $pattern     = '/\n{' . ( $maxBlankLines + 2 ) . ',}/';

        return preg_replace( $pattern, $replacement, $content );
    }

    /**
     * Prepares PHP file content for merging (strips tags and cleans whitespace).
     *
     * @param string $content The PHP file content.
     *
     * @return string Cleaned content ready for merging.
     */
    public function prepareForMerge( string $content ): string
    {
        $content = $this->stripPhpTags( $content );
        return $this->reduceBlankLines( $content );
    }

    /**
     * Removes all PHP comments from content.
     *
     * @param string $content The content to clean.
     *
     * @return string Content without comments.
     */
    public function stripComments( string $content ): string
    {
        // Entferne mehrzeilige Kommentare /* ... */
        $content = preg_replace( '/\/\*.*?\*\//s', '', $content );

        // Entferne einzeilige Kommentare // ...
        $content = preg_replace( '/\/\/.*$/m', '', $content );

        // Entferne # Kommentare
        $content = preg_replace( '/#.*$/m', '', $content );

        return $this->reduceBlankLines( trim( $content ) );
    }
}
