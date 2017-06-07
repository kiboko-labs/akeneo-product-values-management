<?php

namespace Kiboko\Component\AkeneoProductValues\Config;

use Kiboko\Component\AkeneoProductValues\CodeGenerator\ClassCodeGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ConstantCodeGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\InterfaceCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\StringConstantCodeGenerator;

trait ConstantAwareSpecBuilderTrait
{
    /**
     * @var ConstantCodeGeneratorInterface[]
     */
    private $constants;

    /**
     * @return InterfaceCodeGenerator[]
     */
    public function getConstants(): array
    {
        return $this->constants;
    }

    /**
     * @param callable $filter
     *
     * @return InterfaceCodeGenerator[]
     */
    public function findConstants(callable $filter): array
    {
        return array_filter(
            $this->constants,
            $filter
        );
    }

    /**
     * @param ClassCodeGeneratorInterface $class
     * @param array $values
     */
    public function buildConstants(ClassCodeGeneratorInterface $class, array $values): void
    {
        return;
        foreach ($values as $code => $value) {
            $class->addConstantCodeGenerator(
                new StringConstantCodeGenerator($class, $code, $value)
            );
        }
    }
}
