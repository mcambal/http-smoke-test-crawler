<?php

namespace App\Entity\Simple;

use App\Collection\InputItemCollection;

class OutputConfiguration
{
    /**
     * @var InputItemCollection
     */
    private InputItemCollection $outputProcessors;

    /**
     * @var InputItemCollection
     */
    private InputItemCollection $outputFilters;

    /**
     * OutputConfiguration constructor.
     * @param InputItemCollection $outputProcessors
     * @param InputItemCollection $outputFilters
     */
    public function __construct(InputItemCollection $outputProcessors, InputItemCollection $outputFilters)
    {
        $this->outputProcessors = $outputProcessors;
        $this->outputFilters = $outputFilters;
    }

    /**
     * @return array
     */
    public function getOutputProcessors(): array
    {
        return $this->outputProcessors->all();
    }

    /**
     * @return array|string[]
     */
    public function getOutputFilters(): array
    {
        return $this->outputFilters->all();
    }
}
