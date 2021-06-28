<?php

namespace App\Entity\Simple;

class CrawlerConfiguration
{
    /**
     * @var string
     */
    private string $userAgent = 'SmokeTestCrawler/1.0';
    /**
     * @var int
     */
    private int $delayBetweenRequests = 1;
    /**
     * @var bool
     */
    private bool $respectRobots = true;
    /**
     * @var bool
     */
    private bool $rejectNoFollowLinks = false;
    /**
     * @var int|null
     */
    private ?int $maximumResponseSize = null;
    /**
     * @var int|null
     */
    private ?int $maximumCrawlCount = null;
    /**
     * @var int|null
     */
    private ?int $maximumCrawlDepth = null;

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     */
    public function setUserAgent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    /**
     * @return int|null
     */
    public function getDelayBetweenRequests(): ?int
    {
        return $this->delayBetweenRequests;
    }

    /**
     * @param int $delayBetweenRequests
     */
    public function setDelayBetweenRequests(int $delayBetweenRequests): void
    {
        if($delayBetweenRequests < 0) {
            throw new \InvalidArgumentException('Deplay between requests can\'t be lower than 0s');
        }

        $this->delayBetweenRequests = $delayBetweenRequests;
    }

    /**
     * @return bool
     */
    public function respectRobots(): bool
    {
        return $this->respectRobots;
    }

    /**
     * @param bool $respectRobots
     */
    public function setRespectRobots(bool $respectRobots): void
    {
        $this->respectRobots = $respectRobots;
    }

    /**
     * @return bool
     */
    public function rejectNoFollowLinks(): bool
    {
        return $this->rejectNoFollowLinks;
    }

    /**
     * @param bool $rejectNoFollowLinks
     */
    public function setRejectNoFollowLinks(bool $rejectNoFollowLinks): void
    {
        $this->rejectNoFollowLinks = $rejectNoFollowLinks;
    }

    /**
     * @return int|null
     */
    public function getMaximumResponseSize(): ?int
    {
        return $this->maximumResponseSize;
    }

    /**
     * @param int $maximumResponseSize
     */
    public function setMaximumResponseSize(int $maximumResponseSize): void
    {
        if($maximumResponseSize <= 0) {
            throw new \InvalidArgumentException('Maximum response size must be greater than 0');
        }

        $this->maximumResponseSize = $maximumResponseSize;
    }

    /**
     * @return int|null
     */
    public function getMaximumCrawlCount(): ?int
    {
        return $this->maximumCrawlCount;
    }

    /**
     * @param int $maximumCrawlCount
     */
    public function setMaximumCrawlCount(int $maximumCrawlCount): void
    {
        if($maximumCrawlCount <= 0) {
            throw new \InvalidArgumentException('Maximum crawl count must be greater than 0');
        }

        $this->maximumCrawlCount = $maximumCrawlCount;
    }

    /**
     * @return int|null
     */
    public function getMaximumCrawlDepth(): ?int
    {
        return $this->maximumCrawlDepth;
    }


    /**
     * @param int $maximumCrawlDepth
     */
    public function setMaximumCrawlDepth(int $maximumCrawlDepth): void
    {
        if($maximumCrawlDepth <= 0) {
            throw new \InvalidArgumentException('Maximum crawl depth must be greater than 0');
        }

        $this->maximumCrawlDepth = $maximumCrawlDepth;
    }
}
