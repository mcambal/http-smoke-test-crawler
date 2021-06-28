<?php

namespace App\Handler;

use App\Contract\WebCrawler;
use App\Entity\Simple\CrawlerConfiguration;
use App\Entity\Simple\OutputConfiguration;
use App\Output\Context\OutputContext;

class CrawlHandler
{
    /**
     * @var WebCrawler
     */
    private WebCrawler $crawler;

    /**
     * @var OutputContext
     */
    private OutputContext $outputContext;

    /**
     * CrawlHandler constructor.
     * @param WebCrawler $crawler
     * @param OutputContext $outputContext
     */
    public function __construct(WebCrawler $crawler, OutputContext $outputContext)
    {
        $this->crawler = $crawler;
        $this->outputContext = $outputContext;
    }

    /**
     * @param string $baseUrl
     * @param CrawlerConfiguration $crawlerConfiguration
     * @param OutputConfiguration $outputConfiguration
     * @throws \App\Exception\UnableToFindOutputTypeException
     * @throws \App\Exception\UnableToFindOutputFilterException
     */
    public function crawl(string $baseUrl, CrawlerConfiguration $crawlerConfiguration, OutputConfiguration $outputConfiguration): void
    {
        $this->outputContext->setOutputTypeStrategy($outputConfiguration->getOutputType());
        $this->outputContext->setOutputFilterStrategy($outputConfiguration->getOutputFilters());

        $this->crawler
            ->setOutputContext($this->outputContext)
            ->setConfiguration($crawlerConfiguration)
            ->crawl($baseUrl);
    }
}
