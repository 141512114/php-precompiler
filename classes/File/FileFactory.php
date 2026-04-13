<?php

namespace General\File;

class FileFactory
{
    private FileHandler  $handler;
    private FileAnalyzer $analyzer;
    private FileSanitizer $sanitizer;

    public function __construct(
        FileHandler $handler,
        FileAnalyzer $analyzer,
        FileSanitizer $sanitizer
    )
    {
        $this->handler  = $handler;
        $this->analyzer = $analyzer;
        $this->sanitizer = $sanitizer;
    }

    /**
     * Creates a new File instance.
     *
     * @param string $path Absolute path to the file.
     *
     * @return File
     */
    public function createFile( string $path ): File
    {
        return new File(
            $this->handler,
            $this->analyzer,
            $this->sanitizer,
            $path
        );
    }
}
