<?php

namespace App\Contract;

use App\Entity\Simple\CrawlerConfiguration;
use App\Output\Context\OutputContext;

interface WebCrawler
{

    /**
     * @param OutputContext $outputContext
     * @return $this
     */
    public function setOutputContext(OutputContext $outputContext): self;

    /**
     * @param CrawlerConfiguration $configuration
     * @return $this
     */
    public function setConfiguration(CrawlerConfiguration $configuration): self;

    /**
     * @param string $baseUrl
     */
    public function crawl(string $baseUrl): void;
}
