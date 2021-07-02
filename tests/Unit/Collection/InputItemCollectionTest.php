<?php

namespace Tests\Unit\Collection;

use App\Collection\InputItemCollection;
use PHPUnit\Framework\TestCase;

class InputItemCollectionTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function testItIsPossibleToRetrieveFilesFromCollection()
    {
        $collection = new InputItemCollection('value1,         value2');
        $collection->add('value3');

        $this->assertContains('value1', $collection->all());
        $this->assertContains('value2', $collection->all());
        $this->assertContains('value3', $collection->all());
    }
}
