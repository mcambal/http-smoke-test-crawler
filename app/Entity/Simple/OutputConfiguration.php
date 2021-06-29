<?php

namespace App\Entity\Simple;

class OutputConfiguration
{
    /**
     * @var string
     */
    private string $outputType;

    /**
     * @var array
     */
    private array $outputFilters;

    /**
     * OutputConfiguration constructor.
     * @param string $outputType
     * @param array $outputFilters
     */
    public function __construct(string $outputType, array $outputFilters)
    {
        $this->outputType = $outputType;
        $this->outputFilters = $outputFilters;
    }

    /**
     * @return string
     */
    public function getOutputType(): string
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
