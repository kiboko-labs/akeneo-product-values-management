<?php

namespace Kiboko\Component\AkeneoProductValues\Command;

use Kiboko\Component\AkeneoProductValues\Builder\BundleBuilder;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\BundleCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\Extension\ExtensionFileLoaderInstanciationCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\Extension\ExtensionYamlFileLoadingCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ExtensionCodeGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class InitCommand extends Command implements FilesystemAwareInterface, ComposerAwareInterface
{
    use FilesystemAwareTrait;
    use ComposerAwareTrait;

    protected function configure()
    {
        $this
            ->setName('akeneo:init')
            ->setDescription('Initializes an AppBundle for Akeneo')
            ->setHelp("This initializes your AppBundle if it does not already exist.")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var FormatterHelper $formatterHelper */
        $formatterHelper = $this->getHelper('formatter');
        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        $vendor = $this->getComposer()->getConfig()->get('akeneo-appbundle-vendor-name') ?: null;
        $bundle = $this->getComposer()->getConfig()->get('akeneo-appbundle-bundle-name') ?: 'AppBundle';
        $root = $this->getComposer()->getConfig()->get('akeneo-appbundle-root-dir') ?: 'src';

        $className = $vendor . $bundle;
        if ($vendor === '') {
            $namespace = $bundle;
            $path = $bundle . '/';
        } else {
            $namespace = $vendor . '\\Bundle\\' . $bundle;
            $path = $vendor . '/Bundle/' . $bundle . '/';
        }

        $output->writeln($formatterHelper->formatBlock(
            [
                'Confirm building the custom Bundle:',
                'Your vendor namespace: ' . $vendor,
                'Your bundle name: ' . $className,
                'Your bundle class FQN: ' . $namespace . '\\' . $className,
            ],
            'info',
            true
        ));

        $question = new ConfirmationQuestion($formatterHelper->formatSection(
            'Create your App bundle',
            sprintf('Create %1$s bundle in %2$s/%1$s? [Y/n]', $bundle, $root),
            'info'
        ), true);
        if (!$questionHelper->ask($input, $output, $question)) {
            return;
        }

        $bundleClass = new BundleCodeGenerator($className, $namespace);
        $extensionClass = new ExtensionCodeGenerator($className, $namespace);

        $extensionClass->addLoadMethodStatement(
            (new ExtensionFileLoaderInstanciationCodeGenerator('loader', 'container'))->getNode()
        );

        $extensionClass->addLoadMethodStatement(
            (new ExtensionYamlFileLoadingCodeGenerator('loader', 'services.yml'))->getNode()
        );

        $builder = new BundleBuilder();
        $builder->initialize($this->getFilesystem(), $root . '/' . $path);
        $builder->ensureClassExists(
            $className . '.php',
            $namespace.'\\'.$className,
            $bundleClass
        );
        $builder->ensureClassExists(
            'DependencyInjection/' . $vendor . str_replace('Bundle', 'Extension', $bundle) . '.php',
            $namespace.'\\DependencyInjection\\'.$vendor.str_replace('Bundle', 'Extension', $bundle),
            $extensionClass
        );
        $builder->setConfigFile('Resources/config/services.yml', [
            'parameters' => [],
            'services' => [],
        ]);
        $builder->generate($this->getFilesystem(), $root . '/' . $path);
    }
}
