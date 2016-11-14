<?php

namespace Kiboko\Component\AkeneoProductValues\Command;

use Composer\Composer;

interface ComposerAwareInterface
{
    /**
     * @return Composer
     */
    public function getComposer();

    /**
     * @param Composer $composer
     */
    public function setComposer(Composer $composer);
}
