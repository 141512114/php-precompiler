<?php

namespace General;

class File
{
    private FileHandler  $handler;
    private FileAnalyzer $analyzer;

    private string $path;

    public function __construct(
        FileHandler  $handler,
        FileAnalyzer $analyzer,
        string       $path
    ) {
        $this->handler  = $handler;
        $this->analyzer = $analyzer;

        $this->setPath($path);
    }

    public function getContent(): false|string
    {
        $content = $this->fileHandler()->getFileContents($this->getPath());
        return $this->fileHandler()->sanitizeContents($content);
    }

    ####################################
    # Setter
    ####################################

    private function setPath(string $path): void
    {
        $this->path = $path;
    }

    ####################################
    # Getter
    ####################################

    private function fileHandler(): FileHandler
    {
        return $this->handler;
    }

    private function fileAnalyzer(): FileAnalyzer
    {
        return $this->analyzer;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}