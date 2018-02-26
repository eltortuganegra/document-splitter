<?php

namespace DocumentSplitter;

class DocumentSplitter
{
    private $pathToFile;

    public function __construct($pathToFile)
    {
        $this->pathToFile = $pathToFile;
    }

    public function getPathToFile()
    {
        return $this->pathToFile;
    }
}