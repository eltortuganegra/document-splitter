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

        $register = $documentAnalyzer->getRegister();

        $this->assertEquals($content, $register);
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

    public function testALineWithThreePhrasesMustProduceThreeRegisters()
    {
        $content = 'This must be a register. This must be another register. This is third phrase.';
        $documentLine = new DocumentLine($content);
        $documentAnalyzer = new DocumentLineAnalyzer();
        $documentAnalyzer->analyze($documentLine);
        $amountRegistersFound = $documentAnalyzer->getAmountRegistersFound();

        $this->assertEquals(3, $amountRegistersFound);
    }

}
