<?php

namespace Kiboko\Component\AkeneoProductValues\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;
use Kiboko\Component\AkeneoProductValues\Builder\RuleInterface;
use Kiboko\Component\AkeneoProductValues\Command;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

class CommandProvider implements CommandProviderCapability
{
    /**
     * @var Composer
     */
    private $composer;

    /**
     * @var IOInterface
     */
    private $io;

    /**
     * CommandProvider constructor.
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function __construct(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    public function getCommands()
    {
        $filesystem = new Filesystem(
            new Local(getcwd())
        );

        return [
            new DecoratedCommand(
                new Command\InitCommand(),
                $filesystem,
                $this->composer
            ),
//            new DecoratedCommand(
//                new Command\ReferenceData\AddCommand(
//                    null,
//                    $this->listRules()
//                ),
//                $filesystem,
//                $this->composer
//            ),
//            new DecoratedCommand(
//                new Command\ReferenceData\RemoveCommand(
//                    null,
//                    $this->listRules()
//                ),
//                $filesystem,
//                $this->composer
//            ),
            new DecoratedCommand(
                new Command\ReferenceData\ListCommand(),
                $filesystem,
                $this->composer
            ),
            new DecoratedCommand(
                new Command\ReferenceData\BuildCommand(),
                $filesystem,
                $this->composer
            ),
        ];
    }
}
