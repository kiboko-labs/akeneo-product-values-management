<?php

declare(strict_types=1);

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorInterface;
use PhpParser\Builder;

interface AnnotationAwareCodeGeneratorInterface extends Builder
{
    /**
     * @param AnnotationGeneratorInterface[] $constantCodeGenerators
     */
    public function setAnnotationGenerators(array $constantCodeGenerators): void;

    /**
     * @param AnnotationGeneratorInterface $constantCodeGenerator
     */
    public function addAnnotationGenerator(AnnotationGeneratorInterface $constantCodeGenerator): void;

    /**
     * @param AnnotationGeneratorInterface $constantCodeGenerator
     */
    public function removeAnnotationGenerator(AnnotationGeneratorInterface $constantCodeGenerator): void;

    /**
     * @return AnnotationGeneratorInterface[]
     */
    public function getAnnotationGenerators(): array;

    /**
     * @param callable $filter
     *
     * @return AnnotationGeneratorInterface[]
     */
    public function findAnnotationGenerators(callable $filter): array;
}
