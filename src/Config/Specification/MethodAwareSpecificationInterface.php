<?php

namespace Kiboko\Component\AkeneoProductValues\Config\Specification;

use Kiboko\Component\AkeneoProductValues\CodeGenerator\MethodAwareCodeGeneratorInterface;

interface MethodAwareSpecificationInterface extends SpecificationInterface
{
    /**
     * @param MethodAwareCodeGeneratorInterface[] $generators
     */
    public function setMethodGenerator(array $generators): void;

    /**
     * @param MethodAwareCodeGeneratorInterface $generator
     */
    public function addMethodGenerator(MethodAwareCodeGeneratorInterface $generator): void;

    /**
     * @param MethodAwareCodeGeneratorInterface $generator
     */
    public function removeMethodGenerator(MethodAwareCodeGeneratorInterface $generator): void;

    /**
     * @param callable $filter
     *
     * @return MethodAwareCodeGeneratorInterface[]
     */
    public function findMethodGenerators(callable $filter): array;

    /**
     * @return MethodAwareCodeGeneratorInterface[]
     */
    public function getMethodGenerators(): array;
}
