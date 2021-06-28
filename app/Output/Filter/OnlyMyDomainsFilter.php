<?php

namespace App\Output\Filter;

use App\Entity\Simple\CrawlData;
use App\Output\Processor\OutputProcessor;

class OnlyMyDomainsFilter implements OutputFilter
{
    /**
     * @var array
     */
    private array $expectedDomains;

    /**
     * @var array
     */
    private array $supportedProcessors;

    /**
     * StatusCodeFilter constructor.
     * @param array $expectedDomains
     * @param array $supportedProcessors
     */
    public function __construct(array $expectedDomains, array $supportedProcessors = [])
    {
        $this->expectedDomains = $expectedDomains;
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
        foreach($this->expectedDomains as $domain) {
            if(preg_match('/^http(s)?:\/\/' . $domain . '/', $crawlData->getTargetUrl())) {
                return true;
            }
        }

        return false;
    }
}
