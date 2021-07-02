<?php

namespace Tests\Unit\Output\Formatter;

use App\Output\Formatter\CsvFormatter;
use PHPUnit\Framework\TestCase;

class CsvFormatterTest extends TestCase
{
    public function testItIsPossibleToFormatDataToCsvFormat() {
        $formatter = new CsvFormatter();

        $url = 'https://example.com/test';
        $sourceUrl = 'https://example.com';
        $statusCode = 200;

        $this->assertRegExp(
            '@(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2}),' . implode(',', [$sourceUrl, $url, $statusCode]) . '@',
            $formatter->format($url, $sourceUrl, $statusCode)
        );
    }
}
