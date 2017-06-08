<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use PhpParser\Builder;

interface MethodAwareCodeGeneratorInterface extends Builder
{
    /**
     * @param MethodCodeGenerator[] $methodCodeGenerators
     */
    public function setMethodCodeGenerators(array $methodCodeGenerators): void;

    /**
     * @param MethodCodeGenerator $methodCodeGenerator
     */
    public function addMethodCodeGenerator(MethodCodeGenerator $methodCodeGenerator): void;

    /**
     * @param MethodCodeGenerator $methodCodeGenerator
     */
    public function removeMethodCodeGenerator(MethodCodeGenerator $methodCodeGenerator): void;

    /**
     * @return MethodCodeGenerator[]
     */
    public function getMethodCodeGenerators(): array;

    /**
     * @param callable $filter
     *
     * @return MethodCodeGenerator[]
     */
    public function findMethodCodeGenerators(callable $filter): array;
}
