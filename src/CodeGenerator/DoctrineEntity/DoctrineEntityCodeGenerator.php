<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\CodeContext\ClassContext;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ClassCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\FileCodeGenerator;

class DoctrineEntityCodeGenerator extends ClassCodeGenerator
{
    /**
     * @var AnnotationGeneratorInterface[]
     */
    private $doctrineAnnotationGenerators;

    /**
     * ProductValueScalarFieldCodeGenerator constructor.
     *
     * @param FileCodeGenerator $parentGenerator
     * @param ClassContext $classContext
     * @param AnnotationGeneratorInterface[] $doctrineAnnotationGenerators
     */
    public function __construct(
        FileCodeGenerator $parentGenerator,
        ClassContext $classContext,
        array $doctrineAnnotationGenerators = []
    ) {
        parent::__construct($parentGenerator, $classContext);

        $this->doctrineAnnotationGenerators = $doctrineAnnotationGenerators;
    }
}
