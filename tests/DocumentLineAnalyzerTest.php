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

    public function testAPhraseWithQuestionQuotesInTwoLinesMustProduceOneRegister()
    {
        $documentAnalyzer = new DocumentLineAnalyzer();
        $content = 'Why the rum ';
        $documentLineOne = new DocumentLine($content);
        $documentAnalyzer->analyze($documentLineOne);
        $content = 'is gone?';
        $documentLineTwo = new DocumentLine($content);
        $documentAnalyzer->analyze($documentLineTwo);

        $registers = $documentAnalyzer->getRegisters();

        $this->assertEquals('Why the rum is gone?', $registers[0]);
    }

    public function testOnePhraseWithExclamationMarkInALineMustProduceARegister()
    {
        $content = 'I’ve got a jar of dirt!';
        $documentLine = new DocumentLine($content);
        $documentAnalyzer = new DocumentLineAnalyzer();
        $documentAnalyzer->analyze($documentLine);

        $registers = $documentAnalyzer->getRegisters();

        $this->assertEquals($content, $registers[0]);
    }

    public function testOnePhraseWithPointAndQuestionAndExclamationMarkInALineMustProduceThreeRegisters()
    {
        $content = 'I’m captain Jack Sparrow. Why the rum is gone? I’ve got a jar of dirt!';
        $documentLine = new DocumentLine($content);
        $documentAnalyzer = new DocumentLineAnalyzer();
        $documentAnalyzer->analyze($documentLine);

        $registers = $documentAnalyzer->getRegisters();

        $this->assertEquals('I’m captain Jack Sparrow.', $registers[0]);
        $this->assertEquals('Why the rum is gone?', $registers[1]);
        $this->assertEquals('I’ve got a jar of dirt!', $registers[2]);
    }

    public function testOnePhraseWithExclamationAndQuestionAndPointMarkInALineMustProduceThreeRegisters()
    {
        $content = 'I’ve got a jar of dirt! Why the rum is gone? I’m captain Jack Sparrow.';
        $documentLine = new DocumentLine($content);
        $documentAnalyzer = new DocumentLineAnalyzer();
        $documentAnalyzer->analyze($documentLine);

        $registers = $documentAnalyzer->getRegisters();

        $this->assertEquals('I’ve got a jar of dirt!', $registers[0]);
        $this->assertEquals('Why the rum is gone?', $registers[1]);
        $this->assertEquals('I’m captain Jack Sparrow.', $registers[2]);
    }

    public function testOnePhraseWithExclamationAndQuestionAndPointMarkInSeveralLinesMustProduceThreeRegisters()
    {
        $documentAnalyzer = new DocumentLineAnalyzer();

        $content = 'I’ve got a jar of ';
        $documentLine = new DocumentLine($content);
        $documentAnalyzer->analyze($documentLine);

        $content = 'dirt! Why the rum is gone? I’m captain Jack Sparrow.';
        $documentLine = new DocumentLine($content);
        $documentAnalyzer->analyze($documentLine);

        $registers = $documentAnalyzer->getRegisters();

        $this->assertEquals('I’ve got a jar of dirt!', $registers[0]);
        $this->assertEquals('Why the rum is gone?', $registers[1]);
        $this->assertEquals('I’m captain Jack Sparrow.', $registers[2]);
    }

    public function testOnePhraseWithPointAndQuotesMustBeProduceARegister()
    {
        $documentAnalyzer = new DocumentLineAnalyzer();

        $content = 'I’m captain Jack Sparrow.';
        $documentLine = new DocumentLine($content);
        $documentAnalyzer->analyze($documentLine);

        $registers = $documentAnalyzer->getRegisters();

        $this->assertEquals('I’m captain Jack Sparrow.', $registers[0]);
    }

    public function testOnePhraseWithExclamationAndQuotesMustBeProduceARegister()
    {
        $documentAnalyzer = new DocumentLineAnalyzer();

        $content = '“I’ve got a jar of dirt!”';
        $documentLine = new DocumentLine($content);
        $documentAnalyzer->analyze($documentLine);

        $registers = $documentAnalyzer->getRegisters();

        $this->assertEquals('“I’ve got a jar of dirt!”', $registers[0]);
    }

    public function testOnePhraseWithInterrogationMarkAndQuotesMustBeProduceARegister()
    {
        $documentAnalyzer = new DocumentLineAnalyzer();

        $content = '“Why the rum is gone?”';
        $documentLine = new DocumentLine($content);
        $documentAnalyzer->analyze($documentLine);

        $registers = $documentAnalyzer->getRegisters();

        $this->assertEquals('“Why the rum is gone?”', $registers[0]);
    }

    public function testTwoPhrasesWithPointQuestionExclamationQuotesMustBeProduceTwoRegister()
    {
        $documentAnalyzer = new DocumentLineAnalyzer();

        $content = '“I’m captain Jack ';
        $documentLine = new DocumentLine($content);
        $documentAnalyzer->analyze($documentLine);
        $content = 'Sparrow.” “Why the rum is gone?”';
        $documentLine = new DocumentLine($content);
        $documentAnalyzer->analyze($documentLine);

        $registers = $documentAnalyzer->getRegisters();

        $this->assertEquals('“I’m captain Jack Sparrow.”', $registers[0]);
        $this->assertEquals('“Why the rum is gone?”', $registers[1]);
    }

}
