<?php

namespace DocumentSplitter;

class Splitter
{
    private $amountRegisters;
    private $document;
    private $documentLineAnalyzer;

    public function __construct($document)
    {
        $this->amountRegisters = 0;
        $this->document = $document;
        $this->documentLineAnalyzer = new DocumentLineAnalyzer();
    }

    public function run()
    {
        $this->document->open();
        while (($documentLine = $this->document->getDocumentLine()) !== false) {
            $this->documentLineAnalyzer->analyze($documentLine);
        }
    }

    public function getAmountRegisters()
    {
        return $this->amountRegisters;
    }
}