<?php

namespace DocumentSplitter;

class DocumentSplitter
{
    private $pathToFile;
    private $newDocumentPath;
    private $message;
    private $content;
    private $splitter;
    private $document;

    public function __construct($pathToFile, $newDocumentPath)
    {
        $this->pathToFile = $pathToFile;
        $this->newDocumentPath = $newDocumentPath;
        $this->content = '';
        $this->document = new Document($pathToFile);
    }

    public function getPathToFile()
    {
        return $this->pathToFile;
    }

    public function run()
    {
        if ($this->isDocumentNotFound()) {
            $this->setFileNotFoundMessage();

            return;
        }
        $this->splitDocument();
        $this->saveNewDocument();
    }

    public function getMessage()
    {
        return $this->message;
    }

    private function isDocumentNotFound()
    {
        return ! $this->document->isFileFound();
    }

    private function setFileNotFoundMessage()
    {
        $this->message = 'File not found';
    }

    public function getTotalLines()
    {
        return $this->totalLines;
    }

    private function saveNewDocument()
    {
        file_put_contents($this->newDocumentPath, $this->content);
    }

    public function getAmountRegisters()
    {
        return $this->splitter->getAmountRegisters();
    }

    private function splitDocument()
    {
        $this->splitter = new Splitter($this->document);
        $this->splitter->run();
    }
}