<?php

namespace Kiboko\Component\AkeneoProductValues\Command\ReferenceData;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\DoctrineColumnAnnotationGenerator;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\DoctrineGeneratedValueAnnotationGenerator;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\DoctrineOneToManyAnnotationGenerator;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\UnparameteredDoctrineAnnotationGenerator;
use Kiboko\Component\AkeneoProductValues\Builder\BundleBuilder;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityReferenceFieldCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityReferenceFieldGetMethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityReferenceFieldSetMethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityScalarFieldCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityScalarFieldGetMethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityScalarFieldSetMethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ProductValueCodeGenerator;
use Kiboko\Component\AkeneoProductValues\Command\ComposerAwareInterface;
use Kiboko\Component\AkeneoProductValues\Command\ComposerAwareTrait;
use Kiboko\Component\AkeneoProductValues\Command\FilesystemAwareInterface;
use Kiboko\Component\AkeneoProductValues\Command\FilesystemAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

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
                'Confirm building the custom ProductValue:',
                'Your vendor namespace: ' . $vendor,
                'Your ProductValue FQCN: ' . $namespace . '\\Model\\ProductValue',
            ],
            'info',
            true
        ));

        $productValueClass = new ProductValueCodeGenerator('ProductValue', $namespace . '\\Model');

        $productValueClass->addInternalField(
            (new DoctrineEntityScalarFieldCodeGenerator('colorCode', 'string',
                [
                    new DoctrineColumnAnnotationGenerator('string'),
                    new UnparameteredDoctrineAnnotationGenerator('Id'),
                    new DoctrineGeneratedValueAnnotationGenerator(),
                ]
            ))
        );

        $productValueClass->addInternalField(
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
        $builder->setFileDefinition('Entity/ProductValue.php',
            [
                $productValueClass->getNode()
            ]
        );
        $builder->initialize($this->getFilesystem(), $root . '/' . $path);
        $builder->generate($this->getFilesystem(), $root . '/' . $path);
    }
}
