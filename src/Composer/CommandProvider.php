<?php

namespace Kiboko\Component\AkeneoProductValues\Composer;

use Composer\Composer;
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
     * @param Composer $composer
     */
    public function __construct(Composer $composer)
    {
        $this->composer = $composer;
    }

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
//                new Command\ReferenceData\AddCommand(
//                    null,
//                    $this->listRules()
//                ),
//                $filesystem
//            ),
//            new DecoratedCommand(
//                new Command\ReferenceData\RemoveCommand(
//                    null,
//                    $this->listRules()
//                ),
//                $filesystem
//            ),
            new DecoratedCommand(
                new Command\ReferenceData\ListCommand(
                    null,
                    $this->listRules()
                ),
                $filesystem
            ),
            new DecoratedCommand(
                new Command\ReferenceData\BuildCommand(),
                $filesystem
            ),
        ];
    }

    private function listRules()
    {
        /** @var RuleCapability[] $capabilities */
        $capabilities = $this->composer->getPluginManager()->getPluginCapabilities(RuleCapability::class);

        /** @var RuleInterface[] $rules */
        $rules = [];
        foreach ($capabilities as $capability) {
            $rules += $capability->getRules($this->composer);
        }

        return $rules + [
            'datetime.single' => 'Kiboko\\AkeneoProductValuesPackage\\Datetime\\Builder\\SingleDatetimeRule',
            'datetime.multi' => 'Kiboko\\AkeneoProductValuesPackage\\Datetime\\Builder\\MultipleDatetimeRule',
        ];
    }
}
