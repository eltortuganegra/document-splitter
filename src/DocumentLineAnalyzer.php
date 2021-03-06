<?php

namespace DocumentSplitter;

class DocumentLineAnalyzer
{
    private $registerSeveralLines;
    private $registers;

    public function __construct()
    {
        $this->resetRegisterPreviousLine();
        $this->registers = [];
    }

    public function analyze(DocumentLine $documentLine)
    {
        $registerInitialPosition = 0;
        $endCharacterPosition = $this->findEndCharacterPosition($documentLine);

        if ($this->isEndCharacterPositionNotFound($endCharacterPosition)) {
            $this->registerSeveralLines .= $this->removeReturnCarriage($documentLine->getContent());

            return;
        }

        while($this->isEndCharacterFound($endCharacterPosition)) {
            $length = $this->calculateLengthOfThePhrase($registerInitialPosition, $endCharacterPosition);
            if ($this->isPhraseBetweenQuotesMark($documentLine, $endCharacterPosition)) {
                $length = $this->calculateLengthForQuoteMark($length);
                $endCharacterPosition = $this->calculateEndCharacterPositionForQuoteMark($endCharacterPosition);
            }

            $register = $this->getRegisterFromDocumentLine($documentLine->getContent(), $registerInitialPosition, $length);
            if ($this->didTheRegisterBeginInAPreviousLine()) {
                $register = $this->concatenatePreviousRegisterToRegister($register);
                $this->resetRegisterPreviousLine();
            }

            $this->addRegisterToRegisters($register);
            $registerInitialPosition = $this->calculateInitialPositionForNextRegister($endCharacterPosition);
            $endCharacterPosition = $this->findEndCharacterPosition($documentLine, $registerInitialPosition);

            if ($this->isInitialPositionGreaterThanLengthOfTheDocumentLine($documentLine, $registerInitialPosition)) {
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
        return trim(substr($line, $initialPosition, $length));
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

    private function isInitialPositionGreaterThanLengthOfTheDocumentLine(DocumentLine $documentLine, $initialPosition)
    {
        return $initialPosition > strlen($documentLine->getContent());
    }

    private function didTheRegisterBeginInAPreviousLine()
    {
        return ! empty($this->registerSeveralLines);
    }

    private function calculateInitialPositionForNextRegister($endCharacterPosition)
    {
        $registerInitialPosition = $endCharacterPosition + 1;

        return $registerInitialPosition;
    }

    private function concatenatePreviousRegisterToRegister($register)
    {
        $register = $this->registerSeveralLines . $register;

        return $register;
    }

    private function resetRegisterPreviousLine()
    {
        $this->registerSeveralLines = '';
    }

    private function isPhraseBetweenQuotesMark(DocumentLine $documentLine, $endCharacterPosition)
    {
        $documentLineContent = $documentLine->getContent();
        $documentLineContentSize = $this->getLengthOfDocumentLineContent($documentLineContent);
        $nextCharacterToEndCharacterPosition = $this->calculateInitialPositionForNextRegister($endCharacterPosition);

        return ($this->isEndCharacterPositionLastPositionOfTheDocumentLine($nextCharacterToEndCharacterPosition, $documentLineContentSize))
            && ($this->isLastCharacterADoubleQuote($documentLineContent, $nextCharacterToEndCharacterPosition));
    }

    private function isEndCharacterPositionLastPositionOfTheDocumentLine($nextCharacterToEndCharacterPosition, $documentLineContentSize)
    {
        return ($nextCharacterToEndCharacterPosition) <= ($documentLineContentSize - 1);
    }

    private function isLastCharacterADoubleQuote($documentLineContent, $nextCharacterToEndCharacterPosition)
    {
        return substr($documentLineContent, $nextCharacterToEndCharacterPosition, 3) == '”';
    }

    private function getLengthOfDocumentLineContent($documentLineContent)
    {
        return strlen($documentLineContent);
    }

    private function calculateLengthForQuoteMark($length)
    {
        $length += 3;

        return $length;
    }

    private function calculateEndCharacterPositionForQuoteMark($endCharacterPosition)
    {
        $endCharacterPosition += 3;

        return $endCharacterPosition;
    }
}