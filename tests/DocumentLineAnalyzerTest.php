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

}
