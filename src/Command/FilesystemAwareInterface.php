<?php

namespace Kiboko\Component\AkeneoProductValues\Command;

use League\Flysystem\Filesystem;

interface FilesystemAwareInterface
{
    /**
     * @return Filesystem
     */
    public function getFilesystem();

    /**
     * @param Filesystem $filesystem
     *
     * @return $this
     */
    public function setFilesystem(Filesystem $filesystem = null);
}
