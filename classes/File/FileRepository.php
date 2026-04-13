<?php

namespace General\File;

use General\File\Include\FileInclude;
use RuntimeException;

class FileRepository
{
    private FileHandler  $handler;
    private FileAnalyzer $analyzer;

    public function __construct( FileHandler $handler, FileAnalyzer $analyzer )
    {
        $this->handler  = $handler;
        $this->analyzer = $analyzer;
    }

    /**
     * Retrieve all included files and return them as a list of File objects.
     *
     * @return File[]
     */
    public function getAllIncludedFiles(): array
    {
        $filesFound = get_included_files();

        $returnFiles = [];
        foreach ( $filesFound as $filePath ) $returnFiles[] = $this->createFile( $filePath );

        return $returnFiles;
    }

    /**
     * Retrieves a file by its path.
     *
     * @param string $filePath
     *
     * @return File
     */
    public function getFileByPath( string $filePath ): File
    {
        $absolutePath = $this->handler->resolvePath( $filePath );

        if ( $absolutePath === FALSE ) {
            throw new RuntimeException( "File not found: $filePath" );
        }

        return $this->createFile( $absolutePath );
    }

    /**
     * Retrieves a File object from a FileInclude.
     *
     * @param FileInclude $include  The include to resolve.
     * @param string|null $basePath Optional base path for resolving relative paths.
     *
     * @return File
     * @throws RuntimeException If the included file cannot be found.
     */
    public function getFileFromInclude( FileInclude $include, ?string $basePath = NULL ): File
    {
        $resolvedPath = $this->handler->resolveIncludePath( $include, $basePath );

        if ( $resolvedPath === NULL ) {
            throw new RuntimeException( "Included file not found: {$include->getPath()}" );
        }

        return $this->createFile( $resolvedPath );
    }

    /**
     * Retrieves File objects for all includes in a given file.
     *
     * @param File $file The file to analyze for includes.
     *
     * @return File[] Array of File objects for each valid include.
     */
    public function getFilesFromIncludes( File $file ): array
    {
        $includes = $file->getIncludes();
        $basePath = dirname( $file->getPath() );
        $files    = [];

        foreach ( $includes as $include ) {

            try {

                $files[] = $this->getFileFromInclude( $include, $basePath );

            } catch ( RuntimeException $e ) {

                echo( "Error resolving include: {$e->getMessage()}" );
                continue;

            }
        }

        return $files;
    }

    /**
     * Retrieves the current open file and returns it as a File object.
     *
     * @return File
     */
    public function getCurrentFile(): File
    {
        return $this->getFileByPath( $_SERVER[ 'SCRIPT_FILENAME' ] );
    }

    /**
     * Creates a new File instance.
     *
     * @param string $path Absolute path to the file.
     *
     * @return File
     */
    private function createFile( string $path ): File
    {
        return new File(
            $this->handler,
            $this->analyzer,
            $path
        );
    }
}
