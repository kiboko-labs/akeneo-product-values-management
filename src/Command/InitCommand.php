<?php

namespace Kiboko\Component\AkeneoProductValues\Command;

use Kiboko\Component\AkeneoProductValues\Builder\BundleBuilder;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\BundleCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\Extension\ExtensionFileLoaderInstanciationCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\Extension\ExtensionYamlFileLoadingCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ExtensionCodeGenerator;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class InitCommand extends Command implements FilesystemAwareInterface
{
    use FilesystemAwareTrait;

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

        $question = new Question($formatterHelper->formatSection(
            'Create your App bundle',
            'Please enter the vendor namespace of the bundle to create (leave empty for not using namespace)' . PHP_EOL,
            'info'
        ));
        $vendor = $questionHelper->ask($input, $output, $question);

        $question = new Question($formatterHelper->formatSection(
            'Create your App bundle',
            'Please enter the full name of the bundle to create' . PHP_EOL,
            'info'
        ), 'AppBundle');
        $question->setAutocompleterValues(['AppBundle']);
        $question->setValidator(function ($answer) {
            if ('Bundle' !== substr($answer, -6)) {
                throw new \RuntimeException(
                    'The name of the bundle should be suffixed with \'Bundle\''
                );
            }
            return $answer;
        });
        $question->setMaxAttempts(2);
        $bundle = $questionHelper->ask($input, $output, $question);

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
                'Your vendor namespace: ' . $vendor,
                'Your bundle name: ' . $className,
                'Your bundle class FQN: ' . $namespace . '\\' . $className,
            ],
            'info',
            true
        ));

        $question = new ConfirmationQuestion($formatterHelper->formatSection(
            'Create your App bundle',
            sprintf('Create %1$s bundle in src/%1$s? [Y/n]', $bundle),
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
        $builder->setFileDefinition($className . '.php',
            [
                $bundleClass->getNode()
            ]
        );
        $builder->setFileDefinition('DependencyInjection/' . $vendor . str_replace('Bundle', 'Extension', $bundle) . '.php',
            [
                $extensionClass->getNode()
            ]
        );
        $builder->setConfigFile('Resources/config/services.yml', [
            'parameters' => [],
            'services' => [],
        ]);
        $builder->initialize($this->getFilesystem(), 'src/' . $path);
        $builder->generate($this->getFilesystem(), 'src/' . $path);
    }
}
