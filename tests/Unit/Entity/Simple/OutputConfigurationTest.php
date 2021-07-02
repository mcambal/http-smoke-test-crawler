<?php

namespace Tests\Unit\Entity\Simple;

use App\Collection\InputItemCollection;
use App\Entity\Simple\OutputConfiguration;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Collection\InputItemCollectionTest;

class OutputConfigurationTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testItIsPossibleToRetrieveDataFromValueObject()
    {
        $outputProcessors = new InputItemCollection('processor1', 'processor2');
        $outputFilters = new InputItemCollection('filter1', 'filter2');

        $outputConfiguration = new OutputConfiguration($outputProcessors, $outputFilters);

        $this->assertSame($outputProcessors->all(), $outputConfiguration->getOutputProcessors());
        $this->assertSame($outputFilters->all(), $outputConfiguration->getOutputFilters());
    }
}
