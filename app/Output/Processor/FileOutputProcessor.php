<?php

namespace App\Output\Processor;

interface FileOutputProcessor
{
    /**
     * @return string
     */
    public function getFilePath(): string;
}
