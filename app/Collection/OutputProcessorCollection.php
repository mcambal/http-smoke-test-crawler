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
     * @param string $type
     * @param OutputProcessor $outputProcessor
     */
    public function add(string $type, OutputProcessor $outputProcessor)
    {
        $this->outputProcessors[$type][] = $outputProcessor;
    }

    /**
     * @param string $type
     * @return array
     * @throws UnableToFindOutputTypeException
     */
    public function getByType(string $type): array
    {
        if (!isset($this->outputProcessors[$type])) {
            throw new UnableToFindOutputTypeException($type);
        }

        return $this->outputProcessors[$type];
    }
}
