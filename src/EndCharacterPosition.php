<?php

namespace DocumentSplitter;

class EndCharacterPosition
{
    private $documentLine;
    private $offset;
    private $pointPosition;
    private $questionMarkPosition;
    private $exclamationMarkPosition;

    public function __construct(DocumentLine $documentLine, $offset = 0)
    {
        $this->documentLine = $documentLine;
        $this->offset = $offset;
    }

    public function find()
    {
        $this->findPointPosition();
        $this->findQuestionMarkPosition();
        $this->findExclamationMarkPosition();

        if ($this->isPointFoundFirst()) {

            return $this->pointPosition;
        } else if ($this->isQuestionFoundFirst()) {

            return $this->questionMarkPosition;
        } else if ($this->isExclamationFoundFirst()) {

            return $this->exclamationMarkPosition;
        }

        return false;
    }

    private function findPointPosition()
    {
        $endCharacter = '.';
        $this->pointPosition = $this->findEndCharacterPosition($endCharacter);
    }

    private function findEndCharacterPosition($endCharacter)
    {
        $position = strpos($this->documentLine->getContent(), $endCharacter, $this->offset);

        return $position;
    }

    private function findQuestionMarkPosition()
    {
        $endCharacter = '?';
        $this->questionMarkPosition = $this->findEndCharacterPosition($endCharacter);
    }

    private function findExclamationMarkPosition()
    {
        $endCharacter = '!';
        $this->exclamationMarkPosition = $this->findEndCharacterPosition($endCharacter);
    }

    private function isPointFoundFirst()
    {
        return ! empty($this->pointPosition)
            && (
                (
                    empty($this->questionMarkPosition)
                    || ( ! empty($this->questionMarkPosition) && ($this->pointPosition < $this->questionMarkPosition))
                ) && (
                    empty($this->exclamationMarkPosition)
                    || ( ! empty($this->exclamationMarkPosition) && ($this->pointPosition < $this->exclamationMarkPosition))
                )
            );
    }

    private function isQuestionFoundFirst()
    {
        return ! empty($this->questionMarkPosition)
            && (
                (
                    empty($this->pointPosition)
                    || ( ! empty($this->pointPosition) && ($this->questionMarkPosition < $this->pointPosition)
                ) && (
                    empty($this->exclamationMarkPosition)
                    || ( ! empty($this->exclamationMarkPosition) && ($this->questionMarkPosition < $this->exclamationMarkPosition))
                )
            )
        );
    }

    private function isExclamationFoundFirst()
    {
        return ! empty($this->exclamationMarkPosition)
            && (
                (
                    empty($this->pointPosition)
                    || ( ! empty($this->pointPosition) && ($this->exclamationMarkPosition < $this->pointPosition)
                ) && (
                    empty($this->questionMarkPosition)
                    || ( ! empty($this->questionMarkPosition) && ($this->exclamationMarkPosition < $this->questionMarkPosition))
                )
            )
        );
    }
}