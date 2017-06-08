<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

trait MethodAwareCodeGeneratorTrait
{
    /**
     * @var MethodCodeGenerator[]
     */
    private $methodCodeGenerators = [];

    /**
     * @param MethodCodeGenerator[] $methodCodeGenerators
     */
    public function setMethodCodeGenerators(array $methodCodeGenerators): void
    {
        $this->methodCodeGenerators = [];

        foreach ($methodCodeGenerators as $methodCodeGenerator) {
            $this->addMethodCodeGenerator($methodCodeGenerator);
        }
    }

    /**
     * @param MethodCodeGenerator $methodCodeGenerator
     */
    public function addMethodCodeGenerator(MethodCodeGenerator $methodCodeGenerator): void
    {
        if (in_array($methodCodeGenerator, $this->methodCodeGenerators)) {
            return;
        }

        $this->methodCodeGenerators[] = $methodCodeGenerator;
    }

    /**
     * @param MethodCodeGenerator $methodCodeGenerator
     */
    public function removeMethodCodeGenerator(MethodCodeGenerator $methodCodeGenerator): void
    {
        $key = array_search($methodCodeGenerator, $this->methodCodeGenerators);
        if ($key === false) {
            return;
        }

        unset($this->methodCodeGenerators[$key]);
    }

    /**
     * @return MethodCodeGenerator[]
     */
    public function getMethodCodeGenerators(): array
    {
        return $this->methodCodeGenerators;
    }

    /**
     * @param callable $filter
     *
     * @return MethodCodeGenerator[]
     */
    public function findMethodCodeGenerators(callable $filter): array
    {
        return array_filter(
            $this->methodCodeGenerators,
            $filter
        );
    }
}
