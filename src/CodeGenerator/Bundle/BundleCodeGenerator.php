<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator\Bundle;

use Kiboko\Component\AkeneoProductValues\CodeContext\ClassContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\ClassReferenceContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\DefaultValueContext;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ClassCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\FileCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\MethodCodeGenerator;

class BundleCodeGenerator extends ClassCodeGenerator
{
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

        $this->addMethodCodeGenerator(
            $this->prepareBuildMethod(
                new MethodCodeGenerator(
                    $this,
                    'build'
                )
            )
        );
    }

    /**
     * @param MethodCodeGenerator $codeGenerator
     *
     * @return MethodCodeGenerator
     */
    public function prepareBuildMethod(MethodCodeGenerator $codeGenerator): MethodCodeGenerator
    {
        $codeGenerator->setAccess(MethodCodeGenerator::ACCESS_PUBLIC);
        $codeGenerator->buildArgument(
            'container',
            new ClassReferenceContext('Symfony\\Component\\DependencyInjection\\ContainerBuilder'),
            new DefaultValueContext(),
            false
        );

        return $codeGenerator;
    }
}
