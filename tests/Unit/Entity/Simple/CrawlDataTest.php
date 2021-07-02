<?php

namespace Tests\Unit\Entity\Simple;

use App\Entity\Simple\CrawlData;
use PHPUnit\Framework\TestCase;

class CrawlDataTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testItIsPossibleToRetrieveDataFromValueObject()
    {
        $url = 'https://test.url';
        $body = 'body';
        $sourceUrl = 'https://source.url';
        $statusCode = 200;

        $crawlData = new CrawlData($url, $body, $sourceUrl, $statusCode);

        $this->assertSame($url, $crawlData->getTargetUrl());
        $this->assertSame($body, $crawlData->getBody());
        $this->assertSame($sourceUrl, $crawlData->getSourceUrl());
        $this->assertSame($statusCode, $crawlData->getStatusCode());
    }
}
