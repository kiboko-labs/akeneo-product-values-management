<?php


namespace Kiboko\Component\AkeneoProductValues\Filesystem;

use League\Flysystem\File;
use League\Flysystem\Filesystem;
use RecursiveIterator;

class FilesystemIterator implements \RecursiveIterator
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $path;

    /**
     * @var \Iterator;
     */
    private $children;

    /**
     * @param Filesystem $filesystem
     * @param string $path
     */
    public function __construct(Filesystem $filesystem, $path = null)
    {
        $this->filesystem = $filesystem;
        $this->path = $path;

        $this->children = new \ArrayIterator(
            $this->filesystem->listContents($this->path, false)
        );
    }

    /**
     * @return FileInfo
     */
    public function current()
    {
        return new FileInfo($this->filesystem, $this->children->current());
    }

    public function next()
    {
        $this->children->next();
    }

    /**
     * @return string
     */
    public function key()
    {
        return $this->children->key();
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->children->valid();
    }

    public function rewind()
    {
        $this->children->rewind();
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        /** @var File $current */
        $current = $this->children->current();
        return $current->isDir();
    }

    /**
     * @return self
     */
    public function getChildren()
    {
        return new self($this->filesystem, $this->path . '/' . $this->key());
    }
}
