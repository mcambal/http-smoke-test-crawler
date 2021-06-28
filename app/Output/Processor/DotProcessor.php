<?php

namespace App\Output\Processor;

use App\Entity\Simple\CrawlData;

class DotProcessor implements OutputProcessor
{
    /**
     * @param CrawlData $crawlData
     */
    public function write(CrawlData $crawlData): void
    {
        print '.';
    }
}
