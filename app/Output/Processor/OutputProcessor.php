<?php

namespace App\Output\Processor;

use App\Entity\Simple\CrawlData;

interface OutputProcessor
{
    /**
     * @param CrawlData $crawlData
     * @return mixed
     */
    public function write(CrawlData $crawlData);
}
