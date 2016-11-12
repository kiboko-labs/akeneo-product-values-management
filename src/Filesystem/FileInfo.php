<?php

namespace Kiboko\Component\AkeneoProductValues\Filesystem;

use League\Flysystem\Directory;
use League\Flysystem\File;
use League\Flysystem\Filesystem;
use League\Flysystem\Handler;

class FileInfo implements \IteratorAggregate
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var array
     */
    private $options;

    /**
     * FileInfo constructor.
     * @param array $options
     */
    public function __construct(Filesystem $filesystem, array $options)
    {
        $this->filesystem = $filesystem;
        $this->options = $options;
    }

    /**
     * @return Directory|File|Handler
     */
    public function getFile()
    {
        return $this->filesystem->get($this->getPath());
    }

    /**
     * @return FilesystemIterator|null
     */
    public function getIterator()
    {
        if ($this->isDir()) {
            return new FilesystemIterator($this->filesystem, $this->getPath());
        }

        return null;
    }

    /**
     * @return bool
     */
    public function isFile()
    {
        return $this->options['type'] === 'file';
    }

    /**
     * @return bool
     */
    public function isDir()
    {
        return $this->options['type'] === 'dir';
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->options['path'];
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->options['timestamp'];
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDateTime()
    {
        return new \DateTimeImmutable('@'.$this->options['timestamp']);
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->options['size'];
    }

    /**
     * @return int
     */
    public function getDirname()
    {
        return $this->options['dirname'];
    }

    /**
     * @return int
     */
    public function getBasename()
    {
        return $this->options['basename'];
    }

    /**
     * @return int
     */
    public function getExtension()
    {
        return $this->options['extension'];
    }

    /**
     * @return int
     */
    public function getFilename()
    {
        return $this->options['filename'];
    }
}
