<?php

namespace App\Entity\Simple;

class OutputConfiguration
{
    /**
     * @var array
     */
    private array $outputType;

    /**
     * @var array
     */
    private array $outputFilters;

    /**
     * OutputConfiguration constructor.
     * @param array $outputType
     * @param array $outputFilters
     */
    public function __construct(array $outputType, array $outputFilters)
    {
        $this->outputType = $outputType;
        $this->outputFilters = $outputFilters;
    }

    /**
     * @return array
     */
    public function getOutputProcessors(): array
    {
        return $this->outputType;
    }

    /**
     * @return array|string[]
     */
    public function getOutputFilters(): array
    {
        return $this->outputFilters;
    }
}
