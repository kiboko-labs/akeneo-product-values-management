<?php

namespace Kiboko\Component\AkeneoProductValues\Composer;

use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;
use Kiboko\Component\AkeneoProductValues\Command;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

class CommandProvider implements CommandProviderCapability
{
    public function getCommands()
    {
        $filesystem = new Filesystem(
            new Local(getcwd())
        );

        return [
            new DecoratedCommand(
                new Command\InitCommand(),
                $filesystem
            ),
//            new DecoratedCommand(
//                new Command\ReferenceData\AddCommand(),
//                $filesystem
//            ),
            new DecoratedCommand(
                new Command\ReferenceData\RemoveCommand(),
                $filesystem
            ),
//            new DecoratedCommand(
//                new Command\ReferenceData\BuildCommand(),
//                $filesystem
//            ),
        ];
    }
}
