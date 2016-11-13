<?php

namespace Kiboko\Component\AkeneoProductValues\Command;

use League\Flysystem\Filesystem;

trait FilesystemAwareTrait
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * @param Filesystem $filesystem
     *
     * @return $this
     */
    public function setFilesystem(Filesystem $filesystem = null)
    {
        $this->filesystem = $filesystem;
        return $this;
    }
}
