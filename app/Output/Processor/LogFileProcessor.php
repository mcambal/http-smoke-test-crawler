<?php

namespace App\Output\Processor;

use App\Entity\Simple\CrawlData;
use App\Exception\UnableToStoreLogDataException;
use App\Output\Formatter\OutputFormatter;
use App\Output\Processor\Generator\FileNameGenerator;

class LogFileProcessor implements OutputProcessor
{
    private FileNameGenerator $nameGenerator;
    /**
     * @var OutputFormatter
     */
    private OutputFormatter $outputFormatter;

    /**
     * LogFileStrategy constructor.
     * @param FileNameGenerator $nameGenerator
     * @param OutputFormatter $outputFormatter
     */
    public function __construct(FileNameGenerator $nameGenerator, OutputFormatter $outputFormatter)
    {
        $this->nameGenerator = $nameGenerator;
        $this->outputFormatter = $outputFormatter;
    }

    /**
     * @param CrawlData $crawlData
     * @throws UnableToStoreLogDataException
     */
    public function write(CrawlData $crawlData): void
    {
        if (!file_put_contents(
            $this->nameGenerator->getDirectoryPath() . DIRECTORY_SEPARATOR . $this->nameGenerator->getFileName(),
            $this->outputFormatter->format(
                $crawlData->getTargetUrl(),
                $crawlData->getSourceUrl(),
                $crawlData->getStatusCode()
            ), FILE_APPEND)) {
            throw new UnableToStoreLogDataException($this->filePath);
        }
    }
}
