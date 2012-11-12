<?php

namespace GildasQ\Persistence;

class FileSystemPersister implements PersisterInterface
{
    private $path;

    public function __construct($path = null)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function save($data)
    {
        if (!$this->getPath()) {
            throw new \RuntimeException('You haven\'t defined any path to save your data.');
        }

        return file_put_contents($this->getPath(), serialize($data));
    }

    public function retrieve()
    {
        if (file_exists($this->getPath())) {
            return unserialize(file_get_contents($this->getPath()));
        }

        return [];
    }
}
