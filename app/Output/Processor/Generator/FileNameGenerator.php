<?php

namespace App\Output\Processor\Generator;

interface FileNameGenerator
{
    public function getDirectoryPath(): string;
    public function getFileName(): string;
}
