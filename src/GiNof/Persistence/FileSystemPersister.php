<?php

namespace GiNof\Persistence;

class FileSystemPersister implements PersisterInterface
{
    private $path;

    /**
     * @return the path where to store data
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path the path where to store the data
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @{inheritDoc}
     */
    public function save($data)
    {
        if (!$this->getPath()) {
            throw new \RuntimeException('You haven\'t defined any path to save your data.');
        }

        return $this->writeFile($data);
    }

    /**
     * @{inheritDoc}
     */
    public function retrieve()
    {
        if ($this->fileExists()) {
            return $this->readFile();
        }

        return null;
    }

    public function getLastModified()
    {
        if ($this->fileExists()) {
            return date('r', filemtime($this->getPath()));
        }

        return null;
    }

    protected function writeFile($data)
    {
        return file_put_contents($this->getPath(), serialize($data));
    }

    protected function readFile()
    {
        return unserialize(file_get_contents($this->getPath()));
    }

    protected function fileExists()
    {
        return file_exists($this->getPath());
    }
}
