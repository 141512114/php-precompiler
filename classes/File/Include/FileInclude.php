<?php

namespace General\File\Include;

class FileInclude
{
    private IncludeType $includeType;

    private ?string $fullMatch;

    private ?string $dirConstant;

    private ?string $path;

    private int $start;
    private int $end;
    private int $lineNumber;

    public function __construct( IncludeType|string $type = IncludeType::INCLUDE, ?string $path = NULL )
    {
        $this->setIncludeType( $type );
        $this->path = $path;
    }

    ####################################
    # Setter
    ####################################

    public function setIncludeType( IncludeType|string $type ): self
    {
        if ( is_string( $type ) ) {
            $type = IncludeType::tryFrom( $type );
        }
        $this->includeType = $type;
        return $this;
    }

    public function setFullMatch( string $fullMatch ): self
    {
        $this->fullMatch = $fullMatch;
        return $this;
    }

    public function setDirConstant( string $dirConstant ): self
    {
        $this->dirConstant = $dirConstant;
        return $this;
    }

    public function setStart( int $start ): self
    {
        $this->start = $start;
        return $this;
    }

    public function setEnd( int $end ): self
    {
        $this->end = $end;
        return $this;
    }

    public function setLineNumber( int $lineNumber ): self
    {
        $this->lineNumber = $lineNumber;
        return $this;
    }

    ####################################
    # Getter
    ####################################

    public function getIncludeType(): IncludeType
    {
        return $this->includeType;
    }

    public function getFullMatch(): ?string
    {
        return $this->fullMatch;
    }

    public function getDirConstant(): ?string
    {
        return $this->dirConstant;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getStart(): int
    {
        return $this->start;
    }

    public function getEnd(): int
    {
        return $this->end;
    }

    public function getLineNumber(): int
    {
        return $this->lineNumber;
    }
}
