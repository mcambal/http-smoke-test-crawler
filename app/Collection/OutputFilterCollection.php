<?php

namespace App\Collection;

use App\Exception\UnableToFindOutputFilterException;
use App\Output\Filter\OutputFilter;

class OutputFilterCollection
{
    /**
     * @var array
     */
    private array $outputFilters = [];


    /**
     * @param string $name
     * @param OutputFilter $outputFilter
     */
    public function add(string $name, OutputFilter $outputFilter): void
    {
        $this->outputFilters[$name] = $outputFilter;
    }

    /**
     * @param string $name
     * @return OutputFilter
     */
    public function get(string $name): OutputFilter
    {
        if (!isset($this->outputFilters[$name])) {
            throw new UnableToFindOutputFilterException($name);
        }

        return $this->outputFilters[$name];
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->outputFilters;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->outputFilters);
    }
}
