<?php

namespace DocumentSplitter;

class Document
{
    private $path;
    private $handle;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function isFileFound()
    {
        return file_exists($this->path);
    }

    public function open()
    {
        $this->handle = fopen($this->path, "r");
    }

    public function close()
    {
        fclose($this->handle);
    }

    public function getLine()
    {
        $line = fgets($this->handle);

        return $line;
    }

    public function getDocumentLine()
    {
        $line = $this->getLine();
        if (empty($line)) {

            return false;
        }

        return new DocumentLine($line);
    }
}