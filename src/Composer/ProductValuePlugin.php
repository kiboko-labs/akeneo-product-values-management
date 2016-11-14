<?php

namespace Kiboko\Component\AkeneoProductValues\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Kiboko\Component\AkeneoProductValues\Builder\BundleBuilder;

class ProductValuePlugin implements PluginInterface, Capable
{
    public function activate(Composer $composer, IOInterface $io)
    {
    }

    public function getCapabilities()
    {
        return [
            'Composer\\Plugin\\Capability\\CommandProvider' => CommandProvider::class
        ];
    }
}
