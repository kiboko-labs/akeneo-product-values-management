<?php

namespace Kiboko\Component\AkeneoProductValues\Command;

use Composer\Composer;

trait ComposerAwareTrait
{
    /**
     * @var Composer
     */
    private $composer;

    /**
     * @return Composer
     */
    public function getComposer()
    {
        return $this->composer;
    }

    /**
     * @param Composer $composer
     */
    public function setComposer(Composer $composer)
    {
        $this->composer = $composer;
    }
}
