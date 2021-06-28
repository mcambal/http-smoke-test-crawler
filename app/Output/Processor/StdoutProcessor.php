<?php

namespace App\Output\Processor;

use App\Entity\Simple\CrawlData;
use App\Output\Formatter\OutputFormatter;

class StdoutProcessor implements OutputProcessor
{
    /**
     * @var OutputFormatter
     */
    private OutputFormatter $outputFormatter;

    /**
     * StdoutProcessor constructor.
     * @param OutputFormatter $outputFormatter
     */
    public function __construct(OutputFormatter $outputFormatter)
    {
        $this->outputFormatter = $outputFormatter;
    }

    /**
     * @param CrawlData $crawlData
     */
    public function write(CrawlData $crawlData): void
    {
        print $this->outputFormatter->format($crawlData->getTargetUrl(), $crawlData->getSourceUrl(), $crawlData->getStatusCode());
    }
}
