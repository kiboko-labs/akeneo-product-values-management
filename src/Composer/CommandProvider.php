<?php

namespace Kiboko\Component\AkeneoProductValues\Composer;

use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;
use Kiboko\Component\AkeneoProductValues\Command\InitCommand;

class CommandProvider implements CommandProviderCapability
{
    public function getCommands()
    {
        return [
            new InitCommand()
        ];
    }
}
