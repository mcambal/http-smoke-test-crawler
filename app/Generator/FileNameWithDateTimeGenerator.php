<?php

namespace App\Generator;

class FileNameWithDateTimeGenerator implements FileNameGenerator
{
    private string $directoryPath;
    private string $baseName;
    private string $fileExtension;
    private string $datetime;

    public function __construct(string $directoryPath, string $baseName, string $fileExtension, string $dateFormat = 'Y-m-d_H:00:00')
    {
        $this->directoryPath = $directoryPath;
        $this->baseName = $baseName;
        $this->fileExtension = $fileExtension;
        $this->datetime = (new \DateTime())->format($dateFormat);
    }

    public function getDirectoryPath(): string
    {
        return rtrim($this->directoryPath, '/');
    }

    public function getFileName(): string
    {
        return $this->baseName . '-' . $this->datetime . '.' . $this->fileExtension;
    }
}
