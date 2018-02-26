<?php

use PHPUnit\Framework\TestCase;
use DocumentSplitter\DocumentSplitter;

class DocumentSplitterTest extends TestCase
{

    public function testPathToFileMustBeLoaded()
    {
        $pathToFile = './';

        $documentSplitter = new DocumentSplitter($pathToFile);

        $this->assertEquals($pathToFile, $documentSplitter->getPathToFile());
    }
}
