<?php

namespace App\Collection;

use App\Exception\UnableToFindOutputTypeException;
use App\Output\Processor\OutputProcessor;

class OutputProcessorCollection
{
    /**
     * @var array
     */
    private array $outputProcessors = [];

    /**
     * @param string $name
     * @param OutputProcessor $outputProcessor
     */
    public function add(string $name, OutputProcessor $outputProcessor)
    {
        $this->outputProcessors[$name] = $outputProcessor;
    }

    /**
     * @param string $name
     * @return OutputProcessor
     * @throws UnableToFindOutputTypeException
     */
    public function getByName(string $name): OutputProcessor
    {
        if (!isset($this->outputProcessors[$name])) {
            throw new UnableToFindOutputTypeException($name);
        }

        return $this->outputProcessors[$name];
    }
}
