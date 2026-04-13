<?php

namespace General\Cache;

class FileCache
{
    private string $cacheDirectory;
    private int    $cacheTTL;

    public function __construct( string $cacheDirectory = './cache', int $cacheTTL = 3600 )
    {
        $this->cacheDirectory = rtrim( $cacheDirectory, '/\\' );
        $this->cacheTTL       = $cacheTTL;

        if ( !is_dir( $this->cacheDirectory ) ) {
            mkdir( $this->cacheDirectory, 0755, TRUE );
        }
    }

    /**
     * Generates a cache key based on the provided file path.
     *
     * @param string $filePath The path of the file for which to generate the cache key.
     *
     * @return string The generated cache key as an MD5 hash.
     */
    private function getCacheKey( string $filePath ): string
    {
        return md5( realpath( $filePath ) ?: $filePath );
    }

    /**
     * Constructs the full cache file path for the given file.
     *
     * @param string $filePath The path of the file for which to generate the cache file path.
     *
     * @return string The full path to the cache file.
     */
    private function getCachePath( string $filePath ): string
    {
        return $this->cacheDirectory . '/' . $this->getCacheKey( $filePath ) . '.txt';
    }

    /**
     * Checks if a valid cache exists for the given file path.
     *
     * @param string $filePath The path of the file to check for cached data.
     *
     * @return bool
     */
    public function has( string $filePath ): bool
    {
        $cachePath = $this->getCachePath( $filePath );

        if ( !file_exists( $cachePath ) ) {
            return FALSE;
        }

        // Prüfe, ob der Cache noch gültig ist (TTL)
        $cacheTime = filemtime( $cachePath );
        if ( $cacheTime === FALSE || ( time() - $cacheTime ) > $this->cacheTTL ) {
            return FALSE;
        }

        // Prüfe, ob die Originaldatei neuer ist als der Cache
        $originalTime = filemtime( $filePath );
        if ( $originalTime !== FALSE && $originalTime > $cacheTime ) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Stores the provided content in the cache for the given file path.
     *
     * @param string $filePath The path of the file to be cached.
     * @param string $content  The content to be stored in the cache.
     *
     * @return bool
     */
    public function set( string $filePath, string $content ): bool
    {
        $cachePath = $this->getCachePath( $filePath );
        return file_put_contents( $cachePath, $content ) !== FALSE;
    }

    /**
     * Retrieves the content of a cached file based on the provided file path.
     *
     * @param string $filePath The path of the file to retrieve from the cache.
     *
     * @return string|false The content of the cached file as a string, or false if the file does not exist in the cache.
     */
    public function get( string $filePath ): string|false
    {
        if ( !$this->has( $filePath ) ) return FALSE;

        return file_get_contents( $this->getCachePath( $filePath ) );
    }

    /**
     * Deletes the cache file associated with the provided file path.
     *
     * @param string $filePath The path of the file whose associated cache is to be deleted.
     *
     * @return bool
     */
    public function delete( string $filePath ): bool
    {
        $cachePath = $this->getCachePath( $filePath );

        if ( file_exists( $cachePath ) ) return unlink( $cachePath );

        return TRUE;
    }

    /**
     * Clears all cached files in the cache directory with a .txt extension.
     *
     * @return int The number of files successfully removed from the cache directory.
     */
    public function clear(): int
    {
        $count = 0;
        $files = glob( $this->cacheDirectory . '/*.txt' );

        if ( $files === FALSE ) return 0;

        foreach ( $files as $file ) {

            if ( unlink( $file ) ) $count++;

        }

        return $count;
    }
}
