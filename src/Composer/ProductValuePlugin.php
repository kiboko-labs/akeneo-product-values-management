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
        $builder = new BundleBuilder();
        $installer = new ReferenceDataInstaller($io, $composer, $builder);
        $composer->getInstallationManager()->addInstaller($installer);
    }

    public function getCapabilities()
    {
        return [
            'Composer\\Plugin\\Capability\\CommandProvider' => CommandProvider::class
        ];
    }
}
