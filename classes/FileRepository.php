<?php

namespace General;

use RuntimeException;

class FileRepository
{
    private FileHandler  $handler;
    private FileAnalyzer $analyzer;

    public function __construct(FileHandler $handler, FileAnalyzer $analyzer)
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
        foreach ( $filesFound as $filePath ) {

            $file          = new File(
                $this->handler,
                $this->analyzer,
                $filePath
            );
            $returnFiles[] = $file;

        }

        return $returnFiles;
    }

    /**
     * Retrieves a file by its path.
     *
     * @param string $filePath
     *
     * @return File
     */
    public function getFileByPath(string $filePath): File
    {
        $absolutePath = realpath($filePath);

        if ( $absolutePath === FALSE ) {
            throw new RuntimeException("File not found: $filePath");
        }

        return new File(
            $this->handler,
            $this->analyzer,
            $absolutePath
        );
    }

    /**
     * Retrieves the current open file and returns it as a File object.
     *
     * @return File
     */
    public function getCurrentFile(): File
    {
        return $this->getFileByPath($_SERVER['SCRIPT_FILENAME']);
    }
}