<?php

namespace Tests\Unit\Collection;

use App\Collection\FileCollection;
use PHPUnit\Framework\TestCase;

class FileCollectionTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testItIsPossibleToAddFilepathToCollection()
    {
        $collection = new FileCollection();
        $collection->add('/example/path');
        $collection->add('/example/path2');
        $collection->add('/example/path3');

        $this->assertSame(3, $collection->count());
    }

    public function testItIsPossibleToRetrieveFilesFromCollection()
    {
        $filePath = '/example/path';
        $collection = new FileCollection();
        $collection->add($filePath);

        $this->assertContains($filePath, $collection->all());
    }

    public function testItIsPossibleToRetrieveOnlyExistingFilesFromCollection()
    {
        $collection = new FileCollection();
        $collection->add('./tests/Unit/Collection/FileCollectionTest.php');
        $collection->add('./NonExistentFile.xlf');

        $this->assertCount(1, $collection->getNotEmptyFiles());
    }

    public function testItIsPossibleToCountFilesinCollection(){
        $collection = new FileCollection();
        $collection->add('./tests/Unit/Collection/FileCollectionTest.php');
        $collection->add('./tests/Unit/Collection/FileCollectionTest.php');

        $this->assertSame(2, $collection->count());

    }

}
