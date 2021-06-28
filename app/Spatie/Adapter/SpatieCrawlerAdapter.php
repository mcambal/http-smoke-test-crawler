<?php

namespace App\Spatie\Adapter;

use App\Contract\WebCrawler;
use App\Entity\Simple\CrawlerConfiguration;
use App\Output\Context\OutputContext;
use App\Spatie\Strategy\ObserverWithOutputContext;
use Spatie\Crawler\Crawler;

class SpatieCrawlerAdapter implements WebCrawler
{
    /**
     * @var Crawler
     */
    private Crawler $crawler;

    /**
     * SpatieCrawlerAdapter constructor.
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @param OutputContext $outputContext
     * @return $this
     */
    public function setOutputContext(OutputContext $outputContext): self
    {
        $observers = $this->crawler->getCrawlObservers();
        /** @var ObserverWithOutputContext $observer */
        foreach ($observers as $observer) {
            if ($observer instanceof ObserverWithOutputContext) {
                $observer->setOutputContext($outputContext);
            }
        }

        return $this;
    }

    /**
     * @param CrawlerConfiguration $configuration
     * @return $this
     */
    public function setConfiguration(CrawlerConfiguration $configuration): self
    {
        $this->crawler->setUserAgent($configuration->getUserAgent());
        $this->crawler->setDelayBetweenRequests($configuration->getDelayBetweenRequests());

        $this->setRespectRobots($configuration->respectRobots());
        $this->setRejectNoFollowLinks($configuration->rejectNoFollowLinks());
        $this->setMaximumCrawlCount($configuration->getMaximumCrawlCount());
        $this->setMaximumCrawlDepth($configuration->getMaximumCrawlDepth());
        $this->setMaximumResponseSize($configuration->getMaximumResponseSize());

        return $this;
    }

    /**
     * @param bool $respectRobots
     */
    private function setRespectRobots(bool $respectRobots): void
    {
        if ($respectRobots) {
            $this->crawler->respectRobots();
        } else {
            $this->crawler->ignoreRobots();
        }
    }

    /**
     * @param bool $rejectNoFollowLinks
     */
    private function setRejectNoFollowLinks(bool $rejectNoFollowLinks): void
    {
        if ($rejectNoFollowLinks) {
            $this->crawler->rejectNofollowLinks();
        } else {
            $this->crawler->acceptNofollowLinks();
        }
    }

    /**
     * @param int|null $maximumCrawlCount
     */
    private function setMaximumCrawlCount(?int $maximumCrawlCount): void
    {
        if ($maximumCrawlCount !== null) {
            $this->crawler->setMaximumCrawlCount($maximumCrawlCount);
        }

    }

    /**
     * @param int|null $maximumCrawlDepth
     */
    private function setMaximumCrawlDepth(?int $maximumCrawlDepth): void
    {
        if ($maximumCrawlDepth) {
            $this->crawler->setMaximumDepth($maximumCrawlDepth);
        }
    }

    /**
     * @param int|null $maximumResponseSize
     */
    private function setMaximumResponseSize(?int $maximumResponseSize): void
    {
        if ($maximumResponseSize > 0) {
            $this->crawler->setMaximumResponseSize($maximumResponseSize);
        }
    }

    /**
     * @param string $baseUrl
     */
    public function crawl(string $baseUrl): void
    {
        $this->crawler->startCrawling($baseUrl);
    }
}
