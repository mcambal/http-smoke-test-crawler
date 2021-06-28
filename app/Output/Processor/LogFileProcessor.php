<?php

namespace App\Output\Processor;

use App\Entity\Simple\CrawlData;
use App\Exception\UnableToStoreLogDataException;
use App\Output\Formatter\OutputFormatter;

class LogFileProcessor implements OutputProcessor
{
    /**
     * @var string
     */
    private string $filePath;
    /**
     * @var OutputFormatter
     */
    private OutputFormatter $outputFormatter;

    /**
     * LogFileStrategy constructor.
     * @param string $filePath
     * @param OutputFormatter $outputFormatter
     */
    public function __construct(string $filePath, OutputFormatter $outputFormatter)
    {
        $this->filePath = $filePath;
        $this->outputFormatter = $outputFormatter;
    }

    /**
     * @param CrawlData $crawlData
     * @throws UnableToStoreLogDataException
     */
    public function write(CrawlData $crawlData): void
    {
        if (!file_put_contents(
            $this->filePath,
            $this->outputFormatter->format(
                $crawlData->getTargetUrl(),
                $crawlData->getSourceUrl(),
                $crawlData->getStatusCode()
            ), FILE_APPEND)) {
            throw new UnableToStoreLogDataException($this->filePath);
        }
    }
}
