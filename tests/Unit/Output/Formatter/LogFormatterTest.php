<?php

namespace Tests\Unit\Output\Formatter;

use App\Output\Formatter\CsvFormatter;
use App\Output\Formatter\LogFormatter;
use PHPUnit\Framework\TestCase;

class LogFormatterTest extends TestCase
{
    public function testItIsPossibleToFormatDataToLogFormat() {
        $formatter = new LogFormatter();

        $url = 'https://example.com/test';
        $sourceUrl = 'https://example.com';
        $statusCode = 200;

        $this->assertRegExp(
            '@(\[\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2}\]) ' . $sourceUrl . ' -> ' . $url . ' \(' . $statusCode . '\)@',
            $formatter->format($url, $sourceUrl, $statusCode)
        );
    }
}
