<?php

namespace App\Output\Filter;

use App\Entity\Simple\CrawlData;
use App\Output\Processor\OutputProcessor;

class StatusCodeFilter implements OutputFilter
{
    /**
     * @var array
     */
    private array $expectedStatusCodes;

    /**
     * @var array
     */
    private array $supportedProcessors;

    /**
     * StatusCodeFilter constructor.
     * @param array $expectedStatusCodes
     * @param array $supportedProcessors
     */
    public function __construct(array $expectedStatusCodes, array $supportedProcessors = [])
    {
        $this->expectedStatusCodes = $expectedStatusCodes;
        $this->supportedProcessors = $supportedProcessors;
    }

    /**
     * @param OutputProcessor $outputProcessor
     * @return bool
     */
    public function supportsProcessor(OutputProcessor $outputProcessor): bool
    {
        return in_array(get_class($outputProcessor), $this->supportedProcessors);
    }

    /**
     * @param CrawlData $crawlData
     * @return bool
     */
    public function shouldBeProcessed(CrawlData $crawlData): bool
    {
        return in_array($crawlData->getStatusCode(), $this->expectedStatusCodes);
    }
}
