<?php

namespace Tests\Unit\Collection;

use App\Collection\OutputFilterCollection;
use App\Exception\UnableToFindOutputFilterException;
use App\Output\Filter\StatusCodeFilter;
use PHPUnit\Framework\TestCase;

class OutputFilterCollectionTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testItIsPossibleToAddOutputFilterToCollection()
    {
        $outputFilter = $this->createMock(StatusCodeFilter::class);

        $collection = new OutputFilterCollection();
        $collection->add('filter1', $outputFilter);

        $this->assertSame($outputFilter, $collection->get('filter1'));
    }

    public function testItShouldThrowAnExceptionIfFilterDoesntExistInCollection()
    {
        $outputFilter = $this->createMock(StatusCodeFilter::class);

        $collection = new OutputFilterCollection();
        $collection->add('filter1', $outputFilter);

        $this->expectException(UnableToFindOutputFilterException::class);
        $collection->get('filter2');
    }

    public function testItIsPossibleToRetrieveOutputFilterFromCollection()
    {
        $outputFilter1 = $this->createMock(StatusCodeFilter::class);
        $outputFilter2 = $this->createMock(StatusCodeFilter::class);

        $collection = new OutputFilterCollection();
        $collection->add('filter1', $outputFilter1);
        $collection->add('filter2', $outputFilter2);

        $this->assertSame($outputFilter1, $collection->get('filter1'));
        $this->assertSame($outputFilter2, $collection->get('filter2'));
    }

    public function testItIsPossibleToCountOutputFiltersInCollection()
    {
        $outputFilter1 = $this->createMock(StatusCodeFilter::class);
        $outputFilter2 = $this->createMock(StatusCodeFilter::class);

        $collection = new OutputFilterCollection();
        $collection->add('filter1', $outputFilter1);
        $collection->add('filter2', $outputFilter2);

        $this->assertSame(2, $collection->count());
    }
}
