<?php

namespace App\Output\Filter;

use App\Entity\Simple\CrawlData;
use App\Output\Processor\OutputProcessor;

interface OutputFilter
{
    /**
     * @param CrawlData $crawlData
     * @return bool
     */
    public function shouldBeProcessed(CrawlData $crawlData): bool;

    /**
     * @param OutputProcessor $outputProcessor
     * @return bool
     */
    public function supportsProcessor(OutputProcessor $outputProcessor): bool;
}
