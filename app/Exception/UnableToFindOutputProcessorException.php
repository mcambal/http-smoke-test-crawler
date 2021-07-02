<?php

namespace App\Exception;

class UnableToFindOutputProcessorException extends \Exception
{
    /**
     * UnableToFindOutputStrategyException constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct('Unable to find output type (' . $name . ')');
    }
}
