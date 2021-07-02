<?php

namespace App\Entity\Simple;

class OutputConfiguration
{
    /**
     * @var array
     */
    private array $outputProcessors;

    /**
     * @var array
     */
    private array $outputFilters;

    /**
     * OutputConfiguration constructor.
     * @param array $outputProcessors
     * @param array $outputFilters
     */
    public function __construct(array $outputProcessors, array $outputFilters)
    {
        $this->outputProcessors = $outputProcessors;
        $this->outputFilters = $outputFilters;
    }

    /**
     * @return array
     */
    public function getOutputProcessors(): array
    {
        return $this->outputProcessors;
    }

    /**
     * @return array|string[]
     */
    public function getOutputFilters(): array
    {
        return $this->outputFilters;
    }
}
