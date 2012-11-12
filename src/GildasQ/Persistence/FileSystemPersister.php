<?php

namespace GildasQ\Persistence;

class FileSystemPersister implements PersisterInterface
{
    private $path;

    public function __construct($path = null)
    {
        $this->path = $path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function save($data)
    {
        if (!$this->path) {
            throw new \RuntimeException('You haven\'t defined any path to save your data.');
        }

        return file_put_contents($this->path, $data);
    }
}
