<?php

namespace Tests\Unit\Generator;

use App\Generator\BasicFileNameGenerator;
use PHPUnit\Framework\TestCase;

class BasicFileNameGeneratorTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testItIsPossibleToRetrieveDirectoryPathAndFilename()
    {
        $directoryPath = '/test/path';
        $baseName = 'file';
        $extension = 'csv';

        $nameGenerator = new BasicFileNameGenerator($directoryPath, $baseName, $extension);

        $this->assertSame($directoryPath, $nameGenerator->getDirectoryPath());
        $this->assertSame($baseName . '.' . $extension, $nameGenerator->getFileName());
    }

    public function testItIsPossibleToRetrieveDirectoryPathWithoutBackslash() {
        $directoryPath = '/test/path';
        $baseName = 'file';
        $extension = 'csv';

        $nameGenerator = new BasicFileNameGenerator($directoryPath . '////', $baseName, $extension);

        $this->assertSame($directoryPath, $nameGenerator->getDirectoryPath());
    }
}
