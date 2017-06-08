<?php

namespace Kiboko\Component\AkeneoProductValues\Config\Specification;

use Kiboko\Component\AkeneoProductValues\CodeGenerator\ConstantAwareCodeGeneratorInterface;

interface ConstantAwareSpecificationInterface extends SpecificationInterface
{
    /**
     * @param ConstantAwareCodeGeneratorInterface[] $generators
     */
    public function setConstantGenerator(array $generators): void;

    /**
     * @param ConstantAwareCodeGeneratorInterface $generator
     */
    public function addConstantGenerator(ConstantAwareCodeGeneratorInterface $generator): void;

    /**
     * @param ConstantAwareCodeGeneratorInterface $generator
     */
    public function removeConstantGenerator(ConstantAwareCodeGeneratorInterface $generator): void;

    /**
     * @param callable $filter
     *
     * @return ConstantAwareCodeGeneratorInterface[]
     */
    public function findConstantGenerators(callable $filter): array;

    /**
     * @return ConstantAwareCodeGeneratorInterface[]
     */
    public function getConstantGenerators(): array;
}
