<?php

namespace App\Collection;

class FileCollection
{
    /**
     * @var array
     */
    private array $files;

    /**
     * @param string $filePath
     */
    public function add(string $filePath)
    {
        $this->files[] = $filePath;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->files;
    }

    /**
     * @return array
     */
    public function getNotEmptyFiles(): array
    {
        $notEmptyFiles = [];
        foreach ($this->files as $filePath) {
            if (file_exists($filePath) && filesize($filePath) > 0) {
                $notEmptyFiles[] = $filePath;
            }
        }

        return $notEmptyFiles;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->files);
    }
}
