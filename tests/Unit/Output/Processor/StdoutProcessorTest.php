<?php

namespace Tests\Unit\Output\Processor;

use App\Entity\Simple\CrawlData;
use App\Output\Formatter\OutputFormatter;
use App\Output\Processor\StdoutProcessor;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class StdoutProcessorTest extends TestCase
{
    private StdoutProcessor $stdoutProcessor;
    private MockObject $mockOutputFormatter;

    protected function setUp(): void
    {
        $this->mockOutputFormatter = $this->createMock(OutputFormatter::class);
        $this->stdoutProcessor = new StdoutProcessor($this->mockOutputFormatter);
    }

    public function testWriteCallsFormatterAndPrintsOutput(): void
    {
        $targetUrl = 'https://example.com/page';
        $sourceUrl = 'https://example.com';
        $statusCode = 200;
        $body = 'Page content';
        
        $crawlData = new CrawlData($targetUrl, $body, $sourceUrl, $statusCode);
        
        $expectedFormattedOutput = "URL: $targetUrl, Source: $sourceUrl, Status: $statusCode\n";
        
        $this->mockOutputFormatter->expects($this->once())
            ->method('format')
            ->with($targetUrl, $sourceUrl, $statusCode)
            ->willReturn($expectedFormattedOutput);

        // Capture output
        ob_start();
        $this->stdoutProcessor->write($crawlData);
        $actualOutput = ob_get_clean();

        $this->assertEquals($expectedFormattedOutput, $actualOutput);
    }

    public function testWriteHandlesNullValues(): void
    {
        $targetUrl = 'https://example.com/page';
        $sourceUrl = null;
        $statusCode = null;
        $body = null;
        
        $crawlData = new CrawlData($targetUrl, $body, $sourceUrl, $statusCode);
        
        $expectedFormattedOutput = "URL: $targetUrl, Source: null, Status: null\n";
        
        $this->mockOutputFormatter->expects($this->once())
            ->method('format')
            ->with($targetUrl, $sourceUrl, $statusCode)
            ->willReturn($expectedFormattedOutput);

        // Capture output
        ob_start();
        $this->stdoutProcessor->write($crawlData);
        $actualOutput = ob_get_clean();

        $this->assertEquals($expectedFormattedOutput, $actualOutput);
    }

    public function testWriteWithDifferentStatusCodes(): void
    {
        $testCases = [
            ['url' => 'https://example.com/success', 'status' => 200, 'source' => 'https://example.com'],
            ['url' => 'https://example.com/notfound', 'status' => 404, 'source' => 'https://example.com'],
            ['url' => 'https://example.com/error', 'status' => 500, 'source' => 'https://example.com'],
        ];

        foreach ($testCases as $testCase) {
            // Create a fresh mock for each test case
            $mockFormatter = $this->createMock(OutputFormatter::class);
            $processor = new StdoutProcessor($mockFormatter);
            
            $crawlData = new CrawlData($testCase['url'], 'body', $testCase['source'], $testCase['status']);
            
            $expectedOutput = "Formatted: {$testCase['url']} - {$testCase['status']}\n";
            
            $mockFormatter->expects($this->once())
                ->method('format')
                ->with($testCase['url'], $testCase['source'], $testCase['status'])
                ->willReturn($expectedOutput);

            ob_start();
            $processor->write($crawlData);
            $actualOutput = ob_get_clean();

            $this->assertEquals($expectedOutput, $actualOutput);
        }
    }
}