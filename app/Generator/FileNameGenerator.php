<?php

namespace App\Generator;

interface FileNameGenerator
{
    public function getDirectoryPath(): string;
    public function getFileName(): string;
}
