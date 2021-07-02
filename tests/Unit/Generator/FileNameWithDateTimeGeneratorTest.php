<?php

namespace Tests\Unit\Generator;

use App\Generator\FileNameWithDateTimeGenerator;
use PHPUnit\Framework\TestCase;

class FileNameWithDateTimeGeneratorTest extends TestCase
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
        $datetimeFormat = 'Y-m-d_H:00:00';
        $datetime = new \DateTime();

        $nameGenerator = new FileNameWithDateTimeGenerator($directoryPath, $baseName, $extension, $datetimeFormat);

        $this->assertSame($directoryPath, $nameGenerator->getDirectoryPath());
        $this->assertSame($baseName . '-'. $datetime->format($datetimeFormat) . '.' . $extension, $nameGenerator->getFileName());
    }

    public function testItIsPossibleToRetrieveDirectoryPathWithoutBackslash() {
        $directoryPath = '/test/path';
        $baseName = 'file';
        $extension = 'csv';

        $nameGenerator = new FileNameWithDateTimeGenerator($directoryPath . '////', $baseName, $extension);

        $this->assertSame($directoryPath, $nameGenerator->getDirectoryPath());
    }
}
