<?php

namespace App\Output\Context;

use App\Collection\FileCollection;
use App\Collection\OutputFilterCollection;
use App\Collection\OutputProcessorCollection;
use App\Entity\Simple\CrawlData;
use App\Output\Filter\OutputFilter;
use App\Output\Processor\FileOutputProcessor;
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
    public function __construct(OutputProcessorCollection $outputProcessors, OutputFilterCollection $outputFilters)
    {
        $this->outputStrategies = $outputProcessors;
        $this->outputFilters = $outputFilters;
    }

    /**
     * @param array $processors
     * @throws \App\Exception\UnableToFindOutputTypeException
     */
    public function setOutputProcessorStrategy(array $processors): void
    {
        foreach($processors as $processor) {
            $this->selectedOutputProcessors[] = $this->outputStrategies->getByName($processor);
        }
    }

    /**
     * @return FileCollection
     */
    public function getLogFiles(): FileCollection
    {
        $fileCollection = new FileCollection();
        /** @var FileOutputProcessor $outputProcessor */
        foreach ($this->selectedOutputProcessors as $outputProcessor) {
            if ($outputProcessor instanceof FileOutputProcessor) {
                $fileCollection->add($outputProcessor->getFilePath());
            }
        }

        return $fileCollection;
    }

    /**
     * @param array $filters
     * @throws \App\Exception\UnableToFindOutputFilterException
     */
    public function setOutputFilterStrategy(array $filters): void
    {
        foreach ($filters as $filterName) {
            $this->selectedOutputFilters[] = $this->outputFilters->get($filterName);
        }
    }

    /**
     * @param CrawlData $crawlData
     */
    public function write(CrawlData $crawlData): void
    {
        foreach ($this->selectedOutputProcessors as $outputProcessor) {
            if ($this->shouldProcessData($crawlData, $outputProcessor)) {
                $outputProcessor->write($crawlData);
            }
        }
    }

    /**
     * @param CrawlData $crawlData
     * @param OutputProcessor $outputProcessor
     * @return bool
     */
    private function shouldProcessData(CrawlData $crawlData, OutputProcessor $outputProcessor): bool
    {
        if (empty($this->selectedOutputFilters)) {
            return true;
        }

        /** @var OutputFilter $outputFilter */
        foreach ($this->selectedOutputFilters as $outputFilter) {
            if (false === $outputFilter->shouldBeProcessed($crawlData)
                && $outputFilter->supportsProcessor($outputProcessor)) {
                return false;
            }
        }

        return true;
    }
}
