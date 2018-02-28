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
        $registerInitialPosition = 0;
        $endCharacterPosition = $this->findEndCharacterPosition($documentLine);

        if ($this->isEndCharacterPositionNotFound($endCharacterPosition)) {
            $this->registerSeveralLines .= $this->removeReturnCarriage($documentLine->getContent());
            echo " This line has not point. Saved: " . $this->registerSeveralLines . "\n";

            return;
        }

        while($this->isEndCharacterFound($endCharacterPosition)) {
            $length = $this->calculateLengthOfThePhrase($registerInitialPosition, $endCharacterPosition);
            echo "\n Register initial position: $registerInitialPosition | endCharacterPosition: $endCharacterPosition";

            $register = $this->getRegisterFromDocumentLine($documentLine->getContent(), $registerInitialPosition, $length);
            if ($this->didTheRegisterBeginInAPreviousLine()) {
                $register = $this->registerSeveralLines . $register;
            }

            echo "Register: $register";
            $this->addRegisterToRegisters($register);
            $registerInitialPosition = $this->calculateInitialPositionForNextRegister($endCharacterPosition);
            $endCharacterPosition = $this->findEndCharacterPosition($documentLine, $registerInitialPosition);
            if ($this->isInitialPositionGreatherThanLengthOfTheDocumentLine($documentLine, $registerInitialPosition)) {
                $this->registerSeveralLines = $this->getRegisterFromDocumentLine($documentLine->getContent(), $registerInitialPosition, $length);
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

    private function addRegisterToRegisters($register)
    {
        $this->registers[] = $register;
    }

    private function isInitialPositionGreatherThanLengthOfTheDocumentLine(DocumentLine $documentLine, $initialPosition)
    {
        return $initialPosition > strlen($documentLine->getContent());
    }

    private function didTheRegisterBeginInAPreviousLine()
    {
        return !empty($this->registerSeveralLines);
    }

    /**
     * @param $endCharacterPosition
     * @return mixed
     */
    private function calculateInitialPositionForNextRegister($endCharacterPosition)
    {
        $registerInitialPosition = $endCharacterPosition + 1;
        return $registerInitialPosition;
    }
}