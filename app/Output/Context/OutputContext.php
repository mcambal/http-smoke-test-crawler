<?php

namespace App\Output\Context;

use App\Collection\OutputFilterCollection;
use App\Collection\OutputProcessorCollection;
use App\Entity\Simple\CrawlData;
use App\Output\Filter\OutputFilter;
use App\Output\Processor\OutputProcessor;

class OutputContext
{
    /**
     * @var OutputProcessorCollection
     */
    private OutputProcessorCollection $outputStrategies;
    /**
     * @var OutputFilterCollection
     */
    private OutputFilterCollection $outputFilters;

    /**
     * @var array
     */
    private array $selectedOutputProcessors = [];
    /**
     * @var array
     */
    private array $selectedOutputFilters = [];

    /**
     * OutputContext constructor.
     * @param OutputProcessorCollection $outputProcessors
     * @param OutputFilterCollection $outputFilters
     */
    public function __construct(OutputProcessorCollection $outputProcessors, OutputFilterCollection $outputFilters) {
        $this->outputStrategies = $outputProcessors;
        $this->outputFilters = $outputFilters;
    }

    /**
     * @param string $type
     * @throws \App\Exception\UnableToFindOutputTypeException
     */
    public function setOutputTypeStrategy(string $type): void
    {
        $this->selectedOutputProcessors = $this->outputStrategies->getByType($type);
    }

    /**
     * @param array $filters
     * @throws \App\Exception\UnableToFindOutputFilterException
     */
    public function setOutputFilterStrategy(array $filters): void
    {
        foreach($filters as $filterName) {
            $this->selectedOutputFilters[] = $this->outputFilters->get($filterName);
        }
    }

    /**
     * @param CrawlData $crawlData
     */
    public function write(CrawlData $crawlData): void
    {
        foreach ($this->selectedOutputProcessors as $outputProcessor) {
            $this->filterData($crawlData, $outputProcessor);
        }
    }

    /**
     * @param CrawlData $crawlData
     * @param OutputProcessor $outputProcessor
     */
    private function filterData(CrawlData $crawlData, OutputProcessor $outputProcessor): void {

        if(empty($this->selectedOutputFilters)) {
            $outputProcessor->write($crawlData);

            return ;
        }

        /** @var OutputFilter $outputFilter */
        foreach($this->selectedOutputFilters as $outputFilter) {
            if(false === $outputFilter->supportsProcessor($outputProcessor) || $outputFilter->shouldBeProcessed($crawlData)) {
                $outputProcessor->write($crawlData);
            }
        }
    }
}
