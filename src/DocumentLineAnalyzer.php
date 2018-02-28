<?php

namespace DocumentSplitter;

class DocumentLineAnalyzer
{
    private $registerSeveralLines;
    private $registers;

    public function __construct()
    {
        $this->registerSeveralLines = '';
        $this->registers = [];
    }

    public function analyze(DocumentLine $documentLine)
    {
        echo "\nDocument line content: " . $documentLine->getContent() . "\n";
        $initialPosition = 0;
        $endCharacterPosition = $this->findEndCharacterPosition($documentLine);

        if ($this->isEndCharacterPositionNotFound($endCharacterPosition)) {
            $this->registerSeveralLines .= $this->removeReturnCarriage($documentLine->getContent());
            echo " This line has not point. Saved: " . $this->registerSeveralLines . "\n";

            return;
        }

        while($this->isEndCharacterFound($endCharacterPosition)) {
            $length = $this->calculateLengthOfThePhrase($initialPosition, $endCharacterPosition);
            echo "\n Initial position: $initialPosition | endCharacterPosition: $endCharacterPosition";
            $register = $this->getRegisterFromDocumentLine($documentLine->getContent(), $initialPosition, $length);
            if ( ! empty($this->registerSeveralLines)) {
                echo "";
                $register = $this->registerSeveralLines . $register;
            }
            echo "Register: $register";
            $this->registers[] = $register;
            $initialPosition = $endCharacterPosition + 1;
            $endCharacterPosition = $this->findEndCharacterPosition($documentLine, $initialPosition);
            if ($initialPosition > strlen($documentLine->getContent())) {
                $this->registerSeveralLines = $this->getRegisterFromDocumentLine($documentLine->getContent(), $initialPosition, $length);
            }
        }
    }

    private function findEndCharacterPosition(DocumentLine $documentLine, $offset = 0)
    {
        $endCharacterPosition = new EndCharacterPosition($documentLine, $offset);

        return $endCharacterPosition->find();
    }

    private function removeReturnCarriage($line)
    {
        return str_replace("\n", '', $line);
    }

    private function isEndCharacterFound($pointPosition)
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

    public function getRegisters()
    {
        return $this->registers;
    }

    public function getAmountRegistersFound()
    {
        return count($this->registers);
    }

    private function isEndCharacterPositionNotFound($endCharacterPosition)
    {
        return empty($endCharacterPosition);
    }
}