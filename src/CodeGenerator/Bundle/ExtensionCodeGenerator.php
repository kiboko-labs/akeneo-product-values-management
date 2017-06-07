<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use Kiboko\Component\AkeneoProductValues\CodeContext\ClassContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\ClassReferenceContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\DefaultValueContext;
use PhpParser\Builder;

class ExtensionCodeGenerator extends ClassCodeGenerator
{
    /**
     * @var Builder[]
     */
    private $loadMethodCodeGenerators;

    /**
     * @param FileCodeGenerator $parentGenerator
     * @param ClassContext $classContext
     */
    public function __construct(
        FileCodeGenerator $parentGenerator,
        ClassContext $classContext
    ) {
        parent::__construct(
            $parentGenerator,
            $classContext
        );

        $parentGenerator->addUsedReference('Symfony\\Component\\Config\\FileLocator');
        $parentGenerator->addUsedReference('Symfony\\Component\\DependencyInjection\\ContainerBuilder');
        $parentGenerator->addUsedReference('Symfony\\Component\\HttpKernel\\DependencyInjection\\Extension');
        $parentGenerator->addUsedReference('Symfony\\Component\\DependencyInjection\\Loader');

        $classContext->setParentClass(
            new ClassReferenceContext('Symfony\\Component\\HttpKernel\\DependencyInjection\\Extension')
        );

        $this->addMethodCodeGenerator(
            $this->prepareLoadMethod(
                new MethodCodeGenerator(
                    $this,
                    'load'
                )
            )
        );

        $this->loadMethodCodeGenerators = [];
    }

    /**
     * @param MethodCodeGenerator $codeGenerator
     *
     * @return MethodCodeGenerator
     */
    public function prepareLoadMethod(MethodCodeGenerator $codeGenerator): MethodCodeGenerator
    {
        $codeGenerator->setAccess(MethodCodeGenerator::ACCESS_PUBLIC);

        $codeGenerator->buildArgument(
            'configs',
            new ClassReferenceContext('array'),
            new DefaultValueContext(),
            false
        );

        $codeGenerator->buildArgument(
            'container',
            new ClassReferenceContext('Symfony\\Component\\DependencyInjection\\ContainerBuilder'),
            new DefaultValueContext(),
            false
        );

        return $codeGenerator;
    }

    /**
     * @return Builder[]
     */
    public function getLoadMethodGenerators()
    {
        return $this->loadMethodCodeGenerators;
    }

    /**
     * @param Builder $builder
     */
    public function addLoadMethodStatement(Builder $builder)
    {
        $this->loadMethodCodeGenerators[] = $builder;
    }

    /**
     * @param Builder[] $builders
     */
    public function setLoadMethodStatements(array $builders)
    {
        $this->loadMethodCodeGenerators = [];

        foreach ($builders as $builder) {
            $this->addLoadMethodStatement($$builder);
        }
    }
}
