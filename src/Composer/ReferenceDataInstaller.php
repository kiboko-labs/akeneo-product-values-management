<?php

namespace Kiboko\Component\AkeneoProductValues\Composer;

use Composer\Composer;
use Composer\Installer\PluginInstaller;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Installer\InstallerInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Kiboko\Component\AkeneoProductValues\Builder\BundleBuilder;
use Kiboko\Component\AkeneoProductValues\Builder\RuleInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter;

class ReferenceDataInstaller implements InstallerInterface
{
    /**
     * @var IOInterface
     */
    private $io;

    /**
     * @var Composer
     */
    private $composer;

    /**
     * @var BundleBuilder
     */
    private $builder;

    /**
     * @var RuleInterface[]
     */
    private $rules;

    /**
     * @var PluginInstaller
     */
    private $decorated;

    /**
     * DatetimeInstaller constructor.
     * @param IOInterface $io
     * @param Composer $composer
     * @param BundleBuilder $builder
     * @param string $type
     */
    public function __construct(IOInterface $io, Composer $composer, BundleBuilder $builder, $type = 'akeneo-reference-data')
    {
        $this->io = $io;
        $this->composer = $composer;
        $this->builder = $builder;
        $this->rules = [];

        $this->decorated = new PluginInstaller($io, $composer, 'akeneo-reference-data');
    }

    /**
     * @param RuleInterface $rule
     */
    public function registerRule(RuleInterface $rule)
    {
        $this->rules[$rule->getName()] = $rule;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return $packageType === 'akeneo-reference-data' || $this->decorated->supports($packageType);
    }

    /**
     * {@inheritDoc}
     */
    public function isInstalled(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        return $this->decorated->isInstalled($repo, $package);
    }

    /**
     * {@inheritDoc}
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        $this->decorated->install($repo, $package);

        $root = $this->composer->getConfig()->get('akeneo-appbundle-root-dir') ?: 'src';
        $vendor = $this->composer->getConfig()->get('akeneo-appbundle-vendor-name') ?: null;
        $bundle = $this->composer->getConfig()->get('akeneo-appbundle-bundle-name') ?: 'AppBundle';

        if ($vendor === '') {
            $path = $bundle . '/';
        } else {
            $path = $vendor . '/Bundle/' . $bundle . '/';
        }

        $filesystem = new Filesystem(
            new Adapter\Local(getcwd())
        );

        if (!$this->runInteractively($this->builder)) {
            return;
        }

        $this->builder->initialize($filesystem, $root . '/' . $path);
        $this->builder->generate($filesystem, $root . '/' . $path);
    }

    /**
     * {@inheritDoc}
     */
    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
    {
        $this->decorated->update($repo, $initial, $target);

        $root = $this->composer->getConfig()->get('akeneo-appbundle-root-dir') ?: 'src';
        $vendor = $this->composer->getConfig()->get('akeneo-appbundle-vendor-name') ?: null;
        $bundle = $this->composer->getConfig()->get('akeneo-appbundle-bundle-name') ?: 'AppBundle';

        if ($vendor === '') {
            $path = $bundle . '/';
        } else {
            $path = $vendor . '/Bundle/' . $bundle . '/';
        }

        $filesystem = new Filesystem(
            new Adapter\Local(getcwd())
        );

        $builder = new BundleBuilder();
        if (!$this->runInteractively($builder)) {
            return;
        }

        $builder->initialize($filesystem, $root . '/' . $path);
        $builder->generate($filesystem, $root . '/' . $path);
    }

    /**
     * {@inheritDoc}
     */
    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        $this->decorated->uninstall($repo, $package);
    }

    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        return $this->decorated->getInstallPath($package);
    }

    private function runInteractively(BundleBuilder $builder)
    {
        if (!$this->io->askConfirmation('Do you want to add reference data to your Akeneo ProductValue class ?')) {
            return;
        }

        $ruleNames = $this->rules;
        array_walk($ruleNames, function(RuleInterface &$value, $index) {
            $value = sprintf('  %d. <info>%s</info> [%s]', $index, $value->getName(), $value->getReferenceClass());
        });

        $ruleCodes = $this->rules;
        array_walk($ruleNames, function(RuleInterface &$value, $index) {
            $value = $value->getName();
        });

        while (true) {
            $type = $this->io->askAndValidate(
                array_merge(
                    [
                        'Please choose a reference data to add:'
                    ],
                    $ruleNames
                ),
                function ($value) use($ruleCodes) {
                    return (is_numeric($value) && $value > 0 && $value < (count($ruleCodes) - 1)) ||
                    in_array($value, $ruleCodes);
                },
                2
            );

            if (is_numeric($type)) {
                $type = $ruleCodes[$type];
            }

            $this->rules[$type]->applyTo($builder);

            if (!$this->io->askConfirmation('Do you want to add another reference ?')) {
                break;
            }
        }
    }
}
