<?php

namespace App\Exception;

class UnableToFindOutputFilterException extends \Exception
{
    /**
     * UnableToFindOutputStrategyException constructor.
     * @param string $typeKey
     */
    public function __construct(string $name)
    {
        parent::__construct('Unable to find output filter (' . $name . ')');
    }
}
