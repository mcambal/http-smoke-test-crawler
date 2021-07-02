<?php

namespace Tests\Unit\Output\Filter;

use App\Entity\Simple\CrawlData;
use App\Generator\FileNameGenerator;
use App\Output\Filter\MyDomainsFilter;
use App\Output\Filter\StatusCodeFilter;
use App\Output\Formatter\OutputFormatter;
use App\Output\Processor\LogFileProcessor;
use App\Output\Processor\StdoutProcessor;
use PHPUnit\Framework\TestCase;

class MyDomainsFilterTest extends TestCase
{
    public function testItIsPossibleToCheckIfFilterSupportsProcessor()
    {
        $filter = new MyDomainsFilter(['http(s)?:\/\/[^\.]+\.example\.com'], [StdoutProcessor::class]);

        $this->assertTrue(
            $filter->supportsProcessor(
                new StdoutProcessor($this->createMock(OutputFormatter::class))
            )
        );
        $this->assertFalse(
            $filter->supportsProcessor(
                new LogFileProcessor(
                    $this->createMock(FileNameGenerator::class),
                    $this->createMock(OutputFormatter::class)
                )
            )
        );
    }

    public function testItIsPossibleToCheckWhetherCrawlDataShouldBeProcessed()
    {
        $filter = new MyDomainsFilter(['http(s)?:\/\/[^\.]+\.example\.com'], [StdoutProcessor::class]);

        $crawlData = new CrawlData('https://subdomain.example.com/test', null, null, 200);
        $this->assertTrue($filter->shouldBeProcessed($crawlData));
        $crawlData = new CrawlData('https://example.com/test', null, null, 200);
        $this->assertFalse($filter->shouldBeProcessed($crawlData));
    }
}
