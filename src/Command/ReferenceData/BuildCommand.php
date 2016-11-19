<?php

namespace Kiboko\Component\AkeneoProductValues\Command\ReferenceData;

use Kiboko\Component\AkeneoProductValues\Builder\BundleBuilder;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ProductValueCodeGenerator;
use Kiboko\Component\AkeneoProductValues\Command\ComposerAwareInterface;
use Kiboko\Component\AkeneoProductValues\Command\ComposerAwareTrait;
use Kiboko\Component\AkeneoProductValues\Command\FilesystemAwareInterface;
use Kiboko\Component\AkeneoProductValues\Command\FilesystemAwareTrait;
use Kiboko\Component\AkeneoProductValues\Composer\RulesRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildCommand extends Command implements FilesystemAwareInterface, ComposerAwareInterface
{
    use FilesystemAwareTrait;
    use ComposerAwareTrait;

    protected function configure()
    {
        $this
            ->setName('akeneo:reference-data:build')
            ->setDescription('Builds a reference data for your AppBundle\'s ProductValue')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var FormatterHelper $formatterHelper */
        $formatterHelper = $this->getHelper('formatter');

        $vendor = $this->getComposer()->getConfig()->get('akeneo-appbundle-vendor-name') ?: null;
        $bundle = $this->getComposer()->getConfig()->get('akeneo-appbundle-bundle-name') ?: 'AppBundle';
        $root = $this->getComposer()->getConfig()->get('akeneo-appbundle-root-dir') ?: 'src';

        if ($vendor === '') {
            $namespace = $bundle;
            $path = $bundle . '/';
        } else {
            $namespace = $vendor . '\\Bundle\\' . $bundle;
            $path = $vendor . '/Bundle/' . $bundle . '/';
        }

        $output->writeln($formatterHelper->formatBlock(
            [
                'Confirm building the custom ProductValue:',
                'Your vendor namespace: ' . $vendor,
                'Your ProductValue FQCN: ' . $namespace . '\\Model\\ProductValue',
            ],
            'info',
            true
        ));

        $productValueClass = new ProductValueCodeGenerator('ProductValue', $namespace . '\\Model');

        $builder = new BundleBuilder();
        $builder->setFileDefinition('Entity/ProductValue.php',
            [
                $productValueClass->getNode()
            ]
        );
        $builder->initialize($this->getFilesystem(), $root . '/' . $path);
        $builder->generate($this->getFilesystem(), $root . '/' . $path);
    }

    private function getRules()
    {
        $rulesRepository = new RulesRepository(
            $this->getComposer(),
            $this->getComposer()->getPluginManager()
        );

        return $rulesRepository->findAll();
    }
}
