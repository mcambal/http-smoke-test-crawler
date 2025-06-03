<?php

namespace Tests\Unit\Exception;

use App\Exception\UnableToFindOutputFilterException;
use App\Exception\UnableToFindOutputProcessorException;
use App\Exception\UnableToStoreLogDataException;
use PHPUnit\Framework\TestCase;

class CustomExceptionsTest extends TestCase
{
    public function testUnableToFindOutputFilterExceptionMessage(): void
    {
        $filterName = 'NonExistentFilter';
        $exception = new UnableToFindOutputFilterException($filterName);
        
        $expectedMessage = 'Unable to find output filter (' . $filterName . ')';
        $this->assertEquals($expectedMessage, $exception->getMessage());
        $this->assertInstanceOf(\Exception::class, $exception);
    }

    public function testUnableToFindOutputProcessorExceptionMessage(): void
    {
        $processorName = 'NonExistentProcessor';
        $exception = new UnableToFindOutputProcessorException($processorName);
        
        $expectedMessage = 'Unable to find output type (' . $processorName . ')';
        $this->assertEquals($expectedMessage, $exception->getMessage());
        $this->assertInstanceOf(\Exception::class, $exception);
    }

    public function testUnableToStoreLogDataExceptionMessage(): void
    {
        $filePath = '/path/to/nonexistent/file.log';
        $exception = new UnableToStoreLogDataException($filePath);
        
        $expectedMessage = 'Unable to store data to log file (' . $filePath . ')';
        $this->assertEquals($expectedMessage, $exception->getMessage());
        $this->assertInstanceOf(\Exception::class, $exception);
    }

    public function testExceptionsCanBeThrown(): void
    {
        $this->expectException(UnableToFindOutputFilterException::class);
        $this->expectExceptionMessage('Unable to find output filter (TestFilter)');
        
        throw new UnableToFindOutputFilterException('TestFilter');
    }

    public function testExceptionsCanBeCaught(): void
    {
        try {
            throw new UnableToFindOutputProcessorException('TestProcessor');
        } catch (UnableToFindOutputProcessorException $e) {
            $this->assertEquals('Unable to find output type (TestProcessor)', $e->getMessage());
            return;
        }
        
        $this->fail('Exception was not caught');
    }

    public function testExceptionWithEmptyName(): void
    {
        $exception = new UnableToStoreLogDataException('');
        $this->assertEquals('Unable to store data to log file ()', $exception->getMessage());
    }

    public function testExceptionWithSpecialCharacters(): void
    {
        $specialName = 'filter-with_special.chars@123';
        $exception = new UnableToFindOutputFilterException($specialName);
        $this->assertEquals('Unable to find output filter (' . $specialName . ')', $exception->getMessage());
    }
}