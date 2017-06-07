<?php


namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use PhpParser\Builder;

interface ClassCodeGeneratorInterface extends Builder
{
    /**
     * @param ConstantCodeGeneratorInterface[] $constantCodeGenerators
     */
    public function setConstantCodeGenerators(array $constantCodeGenerators): void;

    /**
     * @param ConstantCodeGeneratorInterface $constantCodeGenerator
     */
    public function addConstantCodeGenerator(ConstantCodeGeneratorInterface $constantCodeGenerator): void;

    /**
     * @param ConstantCodeGeneratorInterface $constantCodeGenerator
     */
    public function removeConstantCodeGenerator(ConstantCodeGeneratorInterface $constantCodeGenerator): void;

    /**
     * @return ConstantCodeGeneratorInterface[]
     */
    public function getConstantCodeGenerators(): array;

    /**
     * @param callable $filter
     *
     * @return ConstantCodeGeneratorInterface[]
     */
    public function findConstantCodeGenerators(callable $filter): array;

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
