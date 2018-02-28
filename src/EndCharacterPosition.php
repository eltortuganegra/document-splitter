<?php

namespace DocumentSplitter;

class EndCharacterPosition
{
    private $documentLine;
    private $offset;

    public function __construct(DocumentLine $documentLine, $offset = 0)
    {
        $this->documentLine = $documentLine;
        $this->offset = $offset;
    }

    public function find()
    {
        $pointPosition = $this->findPointPosition();
        $questionMarkPosition = $this->findQuestionMarkPosition();
        $exclamationMarkPosition = $this->findExclamationMarkPosition();

        if ($this->isPointFoundFirst($pointPosition, $questionMarkPosition, $exclamationMarkPosition)) {

            return $pointPosition;
        } else if ($this->isQuestionFoundFirst($questionMarkPosition, $pointPosition, $exclamationMarkPosition)) {

            return $questionMarkPosition;
        } else if ($this->isExclamationFoundFirst($exclamationMarkPosition, $pointPosition, $exclamationMarkPosition)) {

            return $exclamationMarkPosition;
        }

        return false;
    }

    private function findPointPosition()
    {
        $endCharacter = '.';
        $pointPosition = $this->findEndCharacterPosition($endCharacter);

        return $pointPosition;
    }

    private function findEndCharacterPosition($endCharacter)
    {
        $position = strpos($this->documentLine->getContent(), $endCharacter, $this->offset);

        return $position;
    }

    private function findQuestionMarkPosition()
    {
        $endCharacter = '?';
        $questionMarkPosition = $this->findEndCharacterPosition($endCharacter);

        return $questionMarkPosition;
    }

    private function findExclamationMarkPosition()
    {
        $endCharacter = '!';
        $exclamationMarkPosition = $this->findEndCharacterPosition($endCharacter);

        return $exclamationMarkPosition;
    }

    private function isPointFoundFirst($pointPosition, $questionMarkPosition, $exclamationMarkPosition)
    {
        return ! empty($pointPosition)
            && (
                (
                    empty($questionMarkPosition)
                    || ( ! empty($questionMarkPosition) && ($pointPosition < $questionMarkPosition))
                ) && (
                    empty($exclamationMarkPosition)
                    || ( ! empty($exclamationMarkPosition) && ($pointPosition < $exclamationMarkPosition))
                )
            );
    }

    private function isQuestionFoundFirst($questionMarkPosition, $pointPosition, $exclamationMarkPosition)
    {
        return ! empty($questionMarkPosition)
            && (
                (
                    empty($pointPosition)
                    || ( ! empty($pointPosition) && ($questionMarkPosition < $pointPosition)
                ) && (
                    empty($exclamationMarkPosition)
                    || ( ! empty($exclamationMarkPosition) && ($questionMarkPosition < $exclamationMarkPosition))
                )
            )
        );
    }

    private function isExclamationFoundFirst($exclamationMarkPosition, $pointPosition, $exclamationMarkPosition)
    {
        return ! empty($exclamationMarkPosition)
            && (
                (
                    empty($pointPosition)
                    || ( ! empty($pointPosition) && ($exclamationMarkPosition < $pointPosition)
                ) && (
                    empty($questionMarkPosition)
                    || ( ! empty($questionMarkPosition) && ($exclamationMarkPosition < $questionMarkPosition))
                )
            )
        );
    }
}