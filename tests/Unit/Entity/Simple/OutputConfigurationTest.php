<?php

namespace Tests\Unit\Entity\Simple;

use App\Entity\Simple\OutputConfiguration;
use PHPUnit\Framework\TestCase;

class OutputConfigurationTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testItIsPossibleToRetrieveDataFromValueObject()
    {
        $outputProcessors = ['processor1', 'processor2'];
        $outputFilters = ['filter1', 'filter2'];

        $outputConfiguration = new OutputConfiguration($outputProcessors, $outputFilters);

        $this->assertSame($outputProcessors, $outputConfiguration->getOutputProcessors());
        $this->assertSame($outputFilters, $outputConfiguration->getOutputFilters());
    }
}
