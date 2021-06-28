<?php

namespace App\Exception;

class UnableToStoreLogDataException extends \Exception
{
    /**
     * UnableToStoreLogDataException constructor.
     * @param string $logFile
     */
    public function __construct(string $logFile)
    {
        parent::__construct('Unable to store data to log file (' . $logFile . ')');
    }
}
