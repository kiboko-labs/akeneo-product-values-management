<?php

namespace Kiboko\Component\AkeneoProductValues\Command;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\DoctrineColumnAnnotationGenerator;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\DoctrineGeneratedValueAnnotationGenerator;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\DoctrineOneToManyAnnotationGenerator;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\UnparameteredDoctrineAnnotationGenerator;
use Kiboko\Component\AkeneoProductValues\Builder\BundleBuilder;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\BundleCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityReferenceFieldCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityReferenceFieldGetMethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityReferenceFieldSetMethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityScalarFieldCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityScalarFieldGetMethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityScalarFieldSetMethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\Extension\ExtensionFileLoaderInstanciationCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\Extension\ExtensionYamlFileLoadingCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ExtensionCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ProductValueCodeGenerator;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class InitCommand extends Command
{
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
            'Please enter the vendor namespace of the bundle to create' . PHP_EOL,
            'info'
        ), 'Kiboko');
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
        $namespace = $vendor . '\\Bundle\\' . $bundle;

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

        $filesystem = new Filesystem(
            new Local(__DIR__ . '/../../testing')
        );

        $bundleClass = new BundleCodeGenerator($className, $namespace);
        $extensionClass = new ExtensionCodeGenerator($className, $namespace);
        $productValueClass = new ProductValueCodeGenerator('ProductValue', $namespace . '\\Model');

        $extensionClass->addLoadMethodStatement(
            (new ExtensionFileLoaderInstanciationCodeGenerator('loader', 'container'))->getNode()
        );

        $extensionClass->addLoadMethodStatement(
            (new ExtensionYamlFileLoadingCodeGenerator('loader', 'services.yml'))->getNode()
        );

        $productValueClass->addInternalField(
            (new DoctrineEntityScalarFieldCodeGenerator('color', 'string',
                [
                    new DoctrineColumnAnnotationGenerator('string'),
                    new UnparameteredDoctrineAnnotationGenerator('Id'),
                    new DoctrineGeneratedValueAnnotationGenerator(),
                ]
            ))
        );

        $productValueClass->addMethod(
            (new DoctrineEntityReferenceFieldCodeGenerator('color', 'Color', $namespace . '\\Model',
                [
                    new DoctrineOneToManyAnnotationGenerator()
                ]
            ))
        );

        $productValueClass->addMethod(
            (new DoctrineEntityScalarFieldGetMethodCodeGenerator('colorCode', 'string'))
        );

        $productValueClass->addMethod(
            (new DoctrineEntityScalarFieldSetMethodCodeGenerator('colorCode', 'string'))
        );

        $productValueClass->addMethod(
            (new DoctrineEntityScalarFieldGetMethodCodeGenerator('created', 'DateTimeInterface'))
        );

        $productValueClass->addMethod(
            (new DoctrineEntityScalarFieldSetMethodCodeGenerator('created', 'DateTimeInterface'))
        );

        $productValueClass->addMethod(
            (new DoctrineEntityReferenceFieldGetMethodCodeGenerator('color', 'Color', $namespace . '\\Model'))
        );

        $productValueClass->addMethod(
            (new DoctrineEntityReferenceFieldSetMethodCodeGenerator('color', 'Color', $namespace . '\\Model'))
        );

        $productValueClass->addUseStatement('DateTimeInterface');
        $productValueClass->addUseStatement($namespace . '\\Model\\Color');

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
        $builder->setFileDefinition('Model/ProductValue.php',
            [
                $productValueClass->getNode()
            ]
        );
        $builder->setConfigFile('Resources/config/services.yml', [
            'parameters' => [],
            'services' => [],
        ]);
        $builder->initialize($filesystem, 'src/' . $vendor . '/Bundle/' . $bundle . '/');
        $builder->generate($filesystem, 'src/' . $vendor . '/Bundle/' . $bundle . '/');
    }
}
