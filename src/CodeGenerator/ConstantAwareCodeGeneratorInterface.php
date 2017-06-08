<?php

declare(strict_types=1);

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use PhpParser\Builder;

interface ConstantAwareCodeGeneratorInterface extends Builder
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
}
