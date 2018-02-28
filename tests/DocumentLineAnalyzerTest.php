<?php

namespace DocumentSplitter;

use PHPUnit\Framework\TestCase;

class DocumentLineAnalyzerTest extends TestCase
{
    public function testOnePhraseInALineMustProduceARegister()
    {
        $content = 'This must be a register.';
        $documentLine = new DocumentLine($content);
        $documentAnalyzer = new DocumentLineAnalyzer();
        $documentAnalyzer->analyze($documentLine);

        $registers = $documentAnalyzer->getRegisters();

        $this->assertEquals($content, $registers[0]);
    }

    public function testTwoPhrasesInALineMustProduceTwoRegisters()
    {
        $content = 'This must be a register. This must be another register.';
        $documentLine = new DocumentLine($content);
        $documentAnalyzer = new DocumentLineAnalyzer();
        $documentAnalyzer->analyze($documentLine);
        $amountRegistersFound = $documentAnalyzer->getAmountRegistersFound();

        $this->assertEquals(2, $amountRegistersFound);
    }

    public function testAPhraseInTwoLinesMustProduceOneRegister()
    {
        $documentAnalyzer = new DocumentLineAnalyzer();
        $content = 'I am Guybrush Threepwood,';
        $documentLineOne = new DocumentLine($content);
        $documentAnalyzer->analyze($documentLineOne);
        $content = 'mighty pirate.';
        $documentLineTwo = new DocumentLine($content);
        $documentAnalyzer->analyze($documentLineTwo);

        $amountRegistersFound = $documentAnalyzer->getAmountRegistersFound();

        $this->assertEquals(1, $amountRegistersFound);
    }

    public function testAPhraseInTwoLinesMustProduceOneRegisterWithThePhrase()
    {
        $documentAnalyzer = new DocumentLineAnalyzer();
        $content = 'I am Guybrush Threepwood, ';
        $documentLineOne = new DocumentLine($content);
        $documentAnalyzer->analyze($documentLineOne);
        $content = 'mighty pirate.';
        $documentLineTwo = new DocumentLine($content);
        $documentAnalyzer->analyze($documentLineTwo);

        $registers = $documentAnalyzer->getRegisters();

        $this->assertEquals('I am Guybrush Threepwood, mighty pirate.', $registers[0]);
    }

    public function testOnePhraseWithQuestionMarkInALineMustProduceARegister()
    {
        $content = 'Why the rum is gone?';
        $documentLine = new DocumentLine($content);
        $documentAnalyzer = new DocumentLineAnalyzer();
        $documentAnalyzer->analyze($documentLine);

        $registers = $documentAnalyzer->getRegisters();

        $this->assertEquals($content, $registers[0]);
    }



    public function testTwoPhrasesWithQuestionMarkInALineMustProduceARegister()
    {
        $content = 'Why the rum is gone? Why the rum is gone?';
        $documentLine = new DocumentLine($content);
        $documentAnalyzer = new DocumentLineAnalyzer();
        $documentAnalyzer->analyze($documentLine);

        $amountRegistersFound = $documentAnalyzer->getAmountRegistersFound();

        $this->assertEquals(2, $amountRegistersFound);
    }
}
