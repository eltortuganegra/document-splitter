<?php

use PHPUnit\Framework\TestCase;
use DocumentSplitter\DocumentSplitter;

class DocumentSplitterTest extends TestCase
{
    var $pathToFile = __DIR__ . '/documents/document.txt';
    var $newDocumentPath = __DIR__ . '/documents/document_split.txt';

    public function testPathToFileMustBeLoaded()
    {
        $pathToFile = './documents/document.txt';
        $newDocumentPath = './documents/document-split.txt';

        $documentSplitter = new DocumentSplitter($pathToFile, $newDocumentPath);

        $this->assertEquals($pathToFile, $documentSplitter->getPathToFile());
    }

    public function testSetMessageFileNotFoundIfDocumentNotExist()
    {
        $pathToFile = 'NotFoundFile';
        $newDocumentPath = '';
        $documentSplitter = new DocumentSplitter($pathToFile, $newDocumentPath);
        $documentSplitter->run();

        $this->assertEquals('File not found', $documentSplitter->getMessage());
    }

    public function testSplitDefaultDocumentMustGenerateANewDocument()
    {
        $documentSplitter = new DocumentSplitter($this->pathToFile, $this->newDocumentPath);
        $documentSplitter->run();

        $isFileFound = file_exists($this->newDocumentPath);

        $this->assertTrue($isFileFound);
    }

}
