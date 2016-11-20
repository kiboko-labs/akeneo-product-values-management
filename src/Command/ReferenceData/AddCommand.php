<?php

namespace Kiboko\Component\AkeneoProductValues\Command\ReferenceData;

use Kiboko\Component\AkeneoProductValues\Builder\BundleBuilder;
use Kiboko\Component\AkeneoProductValues\Builder\RuleInterface;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ProductValueCodeGenerator;
use Kiboko\Component\AkeneoProductValues\Command\FilesystemAwareInterface;
use Kiboko\Component\AkeneoProductValues\Command\FilesystemAwareTrait;
use Kiboko\Component\AkeneoProductValues\Command\ComposerAwareInterface;
use Kiboko\Component\AkeneoProductValues\Command\ComposerAwareTrait;
use Kiboko\Component\AkeneoProductValues\Composer\RulesRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddCommand extends Command implements FilesystemAwareInterface, ComposerAwareInterface
{
    use FilesystemAwareTrait;
    use ComposerAwareTrait;

    protected function configure()
    {
        $this
            ->setName('akeneo:reference-data:add')
            ->setDescription('Adds a reference data to your AppBundle\'s ProductValue')
        ;

        $this->addArgument(
            'rule',
            InputArgument::REQUIRED,
            'The new property\'s type'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var FormatterHelper $formatterHelper */
        $formatterHelper = $this->getHelper('formatter');

        $vendor = $this->getComposer()->getConfig()->get('akeneo-appbundle-vendor-name') ?: null;
        $bundle = $this->getComposer()->getConfig()->get('akeneo-appbundle-bundle-name') ?: 'AppBundle';
        $root = $this->getComposer()->getConfig()->get('akeneo-appbundle-root-dir') ?: 'src';

        if ($vendor === '') {
            $path = $bundle . '/';
        } else {
            $path = $vendor . '/Bundle/' . $bundle . '/';
        }

        $ruleName = $input->getArgument('rule');
        /** @var RuleInterface $rule */
        if (($rule = $this->getRule($ruleName)) === null) {
            $output->writeln(
                $formatterHelper->formatBlock(
                    [
                        sprintf('The rule "%s" was not found.', $ruleName),
                        'Please check if you have properly required the related composer package'
                    ],
                    'error', true
                )
            );
            return -1;
        }

        $bundleBuilder = new BundleBuilder();
        $bundleBuilder->initialize(
            $this->getFilesystem(),
            $root . '/' . $path
        );
        $rule->interact($input, $output, $this->getComposer());

        $rule->applyTo($bundleBuilder);

        $bundleBuilder->generate(
            $this->getFilesystem(),
            $root . '/' . $path
        );

        return 0;
    }

    private function getRule($name)
    {
        $rulesRepository = new RulesRepository(
            $this->getComposer(),
            $this->getComposer()->getPluginManager()
        );

        return $rulesRepository->findOneByName($name);
    }
}
