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
     * CommandProvider constructor.
     * @param array $args
     */
    public function __construct(array $args)
    {
        if (!$args['composer'] instanceof Composer) {
            throw new \RuntimeException('Expected a "composer" key');
        }

        $this->composer = $args['composer'];
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
            new DecoratedCommand(
                new Command\ReferenceData\AddCommand(),
                $filesystem,
                $this->composer
            ),
//            new DecoratedCommand(
//                new Command\ReferenceData\RemoveCommand(),
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
