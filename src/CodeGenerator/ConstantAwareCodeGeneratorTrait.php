<?php

declare(strict_types=1);

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

trait ConstantAwareCodeGeneratorTrait
{
    /**
     * @var ConstantCodeGeneratorInterface[]
     */
    private $constantCodeGenerators = [];

    /**
     * @param ConstantCodeGeneratorInterface[] $constantCodeGenerators
     */
    public function setConstantCodeGenerators(array $constantCodeGenerators): void
    {
        $this->constantCodeGenerators = [];

        foreach ($constantCodeGenerators as $constantCodeGenerator) {
            $this->addConstantCodeGenerator($constantCodeGenerator);
        }
    }

    /**
     * @param ConstantCodeGeneratorInterface $constantCodeGenerator
     */
    public function addConstantCodeGenerator(ConstantCodeGeneratorInterface $constantCodeGenerator): void
    {
        if (in_array($constantCodeGenerator, $this->constantCodeGenerators)) {
            return;
        }

        $this->constantCodeGenerators[] = $constantCodeGenerator;
    }

    /**
     * @param ConstantCodeGeneratorInterface $constantCodeGenerator
     */
    public function removeConstantCodeGenerator(ConstantCodeGeneratorInterface $constantCodeGenerator): void
    {
        $key = array_search($constantCodeGenerator, $this->constantCodeGenerators);
        if ($key === false) {
            return;
        }

        unset($this->constantCodeGenerators[$key]);
    }

    /**
     * @return ConstantCodeGeneratorInterface[]
     */
    public function getConstantCodeGenerators(): array
    {
        return $this->constantCodeGenerators;
    }

    /**
     * @param callable $filter
     *
     * @return ConstantCodeGeneratorInterface[]
     */
    public function findConstantCodeGenerators(callable $filter): array
    {
        return array_filter(
            $this->constantCodeGenerators,
            $filter
        );
    }
}
