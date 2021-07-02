<?php

namespace Tests\Unit\Output\Context;

use App\Collection\OutputFilterCollection;
use App\Collection\OutputProcessorCollection;
use App\Entity\Simple\CrawlData;
use App\Exception\UnableToFindOutputFilterException;
use App\Exception\UnableToFindOutputProcessorException;
use App\Output\Context\OutputContext;
use App\Output\Filter\StatusCodeFilter;
use App\Output\Processor\LogFileProcessor;
use App\Output\Processor\StdoutProcessor;
use PHPUnit\Framework\TestCase;

class OutputContextTest extends TestCase
{
    public function testItShouldThrowAnExceptionIfProcessorStrategyDoesNotExist()
    {
        $processors = new OutputProcessorCollection();
        $processors->add('stdout', $this->createMock(StdoutProcessor::class));

        $filters = $this->createMock(OutputFilterCollection::class);
        $context = new OutputContext($processors, $filters);

        $this->expectException(UnableToFindOutputProcessorException::class);
        $context->setOutputProcessorStrategy(['stdout2']);
    }

    public function testItShouldThrowAnExceptionIfFilterStrategyDoesNotExist()
    {
        $processors = $this->createMock(OutputProcessorCollection::class);

        $filters = new OutputFilterCollection();
        $filters->add('30x', $this->createMock(StatusCodeFilter::class));

        $context = new OutputContext($processors, $filters);
        $this->expectException(UnableToFindOutputFilterException::class);
        $context->setOutputFilterStrategy(['20x']);
    }

    public function testItIsPossibleToRetrieveFilePathsForLogFiles()
    {
        $examplePath = '/example/path';

        $logFileProcessor = $this->createMock(LogFileProcessor::class);

        $logFileProcessor->expects($this->once())
            ->method('getFilePath')
            ->willReturn($examplePath);

        $processors = new OutputProcessorCollection();
        $processors->add('log', $logFileProcessor);

        $filters = $this->createMock(OutputFilterCollection::class);
        $context = new OutputContext($processors, $filters);
        $context->setOutputProcessorStrategy(['log']);

        $files = $context->getLogFiles();
        $this->assertContains($examplePath, $files->all());
    }

    public function testItIsPossibleToWriteCrawlData()
    {
        $processors = new OutputProcessorCollection();
        $processors->add('stdout', $this->createMock(StdoutProcessor::class));

        $filters = new OutputFilterCollection();
        $filters->add('30x', $this->createMock(StatusCodeFilter::class));

        $context = new OutputContext($processors, $filters);
        $context->setOutputProcessorStrategy(['stdout']);
        $context->setOutputFilterStrategy(['30x']);

        $this->assertNull($context->write($this->createMock(CrawlData::class)));
    }
}
