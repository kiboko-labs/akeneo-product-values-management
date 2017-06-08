<?php

declare(strict_types=1);

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

trait PropertyAwareCodeGeneratorTrait
{
    /**
     * @var PropertyCodeGenerator[]
     */
    private $propertyCodeGenerators = [];

    /**
     * @param PropertyCodeGenerator[] $propertyCodeGenerators
     */
    public function setPropertyCodeGenerators(array $propertyCodeGenerators)
    {
        $this->propertyCodeGenerators = [];

        foreach ($propertyCodeGenerators as $propertyCodeGenerator) {
            $this->addPropertyCodeGenerator($propertyCodeGenerator);
        }
    }

    /**
     * @param PropertyCodeGenerator $propertyCodeGenerator
     */
    public function addPropertyCodeGenerator(PropertyCodeGenerator $propertyCodeGenerator)
    {
        if (in_array($propertyCodeGenerator, $this->propertyCodeGenerators)) {
            return;
        }

        $this->propertyCodeGenerators[] = $propertyCodeGenerator;
    }

    /**
     * @param PropertyCodeGenerator $propertyCodeGenerator
     */
    public function removePropertyCodeGenerator(PropertyCodeGenerator $propertyCodeGenerator): void
    {
        $key = array_search($propertyCodeGenerator, $this->propertyCodeGenerators);
        if ($key === false) {
            return;
        }

        unset($this->propertyCodeGenerators[$key]);
    }

    /**
     * @return PropertyCodeGenerator[]
     */
    public function getPropertyCodeGenerators(): array
    {
        return $this->propertyCodeGenerators;
    }

    /**
     * @param callable $filter
     *
     * @return PropertyCodeGenerator[]
     */
    public function findPropertyCodeGenerators(callable $filter): array
    {
        return array_filter(
            $this->propertyCodeGenerators,
            $filter
        );
    }
}
