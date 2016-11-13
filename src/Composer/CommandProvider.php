<?php

namespace Kiboko\Component\AkeneoProductValues\Composer;

use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;
use Kiboko\Component\AkeneoProductValues\Command;

class CommandProvider implements CommandProviderCapability
{
    public function getCommands()
    {
        return [
            new DecoratedCommand(
                new Command\InitCommand()
            ),
            new DecoratedCommand(
                new Command\ReferenceData\AddCommand()
            ),
            new DecoratedCommand(
                new Command\ReferenceData\RemoveCommand()
            ),
        ];
    }
}
