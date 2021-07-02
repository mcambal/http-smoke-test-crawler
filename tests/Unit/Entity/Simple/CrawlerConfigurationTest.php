<?php

namespace Tests\Unit\Entity\Simple;

use App\Entity\Simple\CrawlerConfiguration;
use PHPUnit\Framework\TestCase;

class CrawlerConfigurationTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testItIsPossibleToRetrieveDataFromValueObject()
    {
        $userAgent = 'MyBot/1.0';
        $delayBetweenRequests = 1;
        $maxCrawlCount = 1000;
        $maxCrawlDepth = 2;
        $maxResponseSize = 1000;
        $rejectNoFollowLinks = true;
        $respectRobots = true;

        $crawlerConfiguration = new CrawlerConfiguration();
        $crawlerConfiguration->setUserAgent($userAgent);
        $crawlerConfiguration->setDelayBetweenRequests($delayBetweenRequests);
        $crawlerConfiguration->setRejectNoFollowLinks($rejectNoFollowLinks);
        $crawlerConfiguration->setMaximumResponseSize($maxResponseSize);
        $crawlerConfiguration->setMaximumCrawlDepth($maxCrawlDepth);
        $crawlerConfiguration->setMaximumCrawlCount($maxCrawlCount);
        $crawlerConfiguration->setRespectRobots($respectRobots);

        $this->assertSame($userAgent, $crawlerConfiguration->getUserAgent());
        $this->assertSame($delayBetweenRequests, $crawlerConfiguration->getDelayBetweenRequests());
        $this->assertSame($maxCrawlCount, $crawlerConfiguration->getMaximumCrawlCount());
        $this->assertSame($maxCrawlDepth, $crawlerConfiguration->getMaximumCrawlDepth());
        $this->assertSame($maxResponseSize, $crawlerConfiguration->getMaximumResponseSize());
        $this->assertSame($rejectNoFollowLinks, $crawlerConfiguration->rejectNoFollowLinks());
        $this->assertSame($respectRobots, $crawlerConfiguration->respectRobots());
    }
}
