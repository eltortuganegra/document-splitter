<?php

namespace DocumentSplitter;

class DocumentLineAnalyzer
{
    private $registerSeveralLines;
    private $register;

    public function __construct()
    {
        $this->registerSeveralLines = '';
    }

    public function analyze(DocumentLine $documentLine)
    {
        echo "\nDocument line content: " . $documentLine->getContent() . "\n";
        $initialPosition = 0;
        $pointPosition = $this->findPointCharacter($documentLine->getContent());
        if (empty($pointPosition)) {
            $this->registerSeveralLines .= $this->removeReturnCarriage($documentLine->getContent());
            echo " This line has not point.\n";

            return;
        }

        while ($this->isPointCharacterFound($pointPosition)) {
            $length = $this->calculateLengthOfThePhrase($initialPosition, $pointPosition);

            echo "\n Initial position: $initialPosition | pointPosition: $pointPosition";
            $this->register = $this->getRegisterFromDocumentLine($documentLine->getContent(), $initialPosition, $length);
            if ( ! empty($this->registerSeveralLines)) {
                $this->register = $this->registerSeveralLines . $this->register;
            }
            echo "\n Register: " . $this->register . "\n";

            $initialPosition = $pointPosition + 1;
            $pointPosition = strpos($documentLine->getContent(), '.', $initialPosition);

            if ($initialPosition > strlen($documentLine->getContent())) {
                $this->registerSeveralLines = $this->getRegisterFromDocumentLine($documentLine->getContent(), $initialPosition, $length);
            }
        }
    }

    private function findPointCharacter($line)
    {
        return strpos($line, '.');
    }

    private function removeReturnCarriage($line)
    {
        return str_replace("\n", '', $line);
    }

    private function isPointCharacterFound($pointPosition)
    {
        return $pointPosition !== false;
    }

    private function calculateLengthOfThePhrase($initialPosition, $pointPosition)
    {
        $length = ($initialPosition == 0)
            ? $pointPosition + 1
            : $pointPosition - $initialPosition + 1;

        return $length;
    }

    private function getRegisterFromDocumentLine($line, $initialPosition, $length)
    {
        return substr($line, $initialPosition, $length);
    }

    public function getRegister()
    {
        return $this->register;
    }
}