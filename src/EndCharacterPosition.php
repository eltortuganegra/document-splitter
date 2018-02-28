<?php

namespace DocumentSplitter;

class EndCharacterPosition
{
    private $line;
    private $offset;

    public function __construct($line, $offset = 0)
    {
        $this->line = $line;
        $this->offset = $offset;
    }

    public function find()
    {
        $pointPosition = $this->findPointPosition();
        $questionMarkPosition = $this->findQuestionMarkPosition();

        if ($this->isPointFoundFirst($pointPosition, $questionMarkPosition)) {

            return $pointPosition;
        } else if ($this->isQuestionFoundFirst($questionMarkPosition, $pointPosition)) {

            return $questionMarkPosition;
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
        $position = strpos($this->line, $endCharacter, $this->offset);

        return $position;
    }

    private function findQuestionMarkPosition()
    {
        $endCharacter = '?';
        $questionMarkPosition = $this->findEndCharacterPosition($endCharacter);

        return $questionMarkPosition;
    }

    private function isPointFoundFirst($pointPosition, $questionMarkPosition)
    {
        return ! empty($pointPosition)
            && (
                empty($questionMarkPosition)
                || (!empty($questionMarkPosition) && ($pointPosition < $questionMarkPosition)
            )
        );
    }

    private function isQuestionFoundFirst($questionMarkPosition, $pointPosition)
    {
        return ! empty($questionMarkPosition)
            && (
                empty($pointPosition)
                || ( ! empty($pointPosition) && ($questionMarkPosition < $pointPosition)
            )
        );
    }
}