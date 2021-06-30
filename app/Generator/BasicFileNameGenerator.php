<?php

namespace App\Generator;

class BasicFileNameGenerator implements FileNameGenerator
{
    private string $directoryPath;
    private string $baseName;
    private string $fileExtension;

    public function __construct(string $directoryPath, string $baseName, string $fileExtension)
    {
        $this->directoryPath = $directoryPath;
        $this->baseName = $baseName;
        $this->fileExtension = $fileExtension;
    }

    public function getDirectoryPath(): string
    {
        return rtrim($this->directoryPath, '/');
    }

    public function getFileName(): string
    {
        return $this->baseName . '.' . $this->fileExtension;
    }
}
