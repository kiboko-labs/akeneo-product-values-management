<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

interface PropertyAwareCodeGeneratorInterface
{
    /**
     * @param PropertyCodeGenerator[] $propertyCodeGenerators
     */
    public function setPropertyCodeGenerators(array $propertyCodeGenerators);

    /**
     * @param PropertyCodeGenerator $propertyCodeGenerator
     */
    public function addPropertyCodeGenerator(PropertyCodeGenerator $propertyCodeGenerator);

    /**
     * @param PropertyCodeGenerator $propertyCodeGenerator
     */
    public function removePropertyCodeGenerator(PropertyCodeGenerator $propertyCodeGenerator): void;

    /**
     * @return PropertyCodeGenerator[]
     */
    public function getPropertyCodeGenerators(): array;

    /**
     * @param callable $filter
     *
     * @return PropertyCodeGenerator[]
     */
    public function findPropertyCodeGenerators(callable $filter): array;
}
