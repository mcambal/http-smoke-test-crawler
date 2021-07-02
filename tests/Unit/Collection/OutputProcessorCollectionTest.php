<?php

namespace Tests\Unit\Collection;

use App\Collection\OutputProcessorCollection;
use App\Exception\UnableToFindOutputProcessorException;
use App\Output\Processor\LogFileProcessor;
use App\Output\Processor\StdoutProcessor;
use PHPUnit\Framework\TestCase;

class OutputProcessorCollectionTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testItIsPossibleToAddOutputProcessorToCollection()
    {
        $outputProcessor = $this->createMock(StdoutProcessor::class);

        $collection = new OutputProcessorCollection();
        $collection->add('processor1', $outputProcessor);

        $this->assertSame($outputProcessor, $collection->getByName('processor1'));
    }

    public function testItShouldThrowAnExceptionIfProcessorDoesntExistInCollection()
    {
        $outputProcessor = $this->createMock(StdoutProcessor::class);

        $collection = new OutputProcessorCollection();
        $collection->add('processor1', $outputProcessor);

        $this->expectException(UnableToFindOutputProcessorException::class);
        $collection->getByName('processor2');
    }

    public function testItIsPossibleToRetrieveOutputProcessorFromCollection()
    {
        $outputProcessor1 = $this->createMock(StdoutProcessor::class);
        $outputProcessor2 = $this->createMock(LogFileProcessor::class);

        $collection = new OutputProcessorCollection();
        $collection->add('processor1', $outputProcessor1);
        $collection->add('processor2', $outputProcessor2);

        $this->assertSame($outputProcessor1, $collection->getByName('processor1'));
        $this->assertSame($outputProcessor2, $collection->getByName('processor2'));
    }
}
