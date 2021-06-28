<?php

namespace App\Entity\Simple;

class OutputConfiguration
{
    /**
     * @var string
     */
    private string $outputType;
    /**
     * @var array|false|string[]
     */
    private array $outputFilters;

    /**
     * OutputConfiguration constructor.
     * @param string $outputType
     * @param string|null $outputFilters
     */
    public function __construct(string $outputType, ?string $outputFilters)
    {
        $this->outputType = $outputType;
        $this->outputFilters = $outputFilters ? explode(',', $outputFilters) : [];
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
