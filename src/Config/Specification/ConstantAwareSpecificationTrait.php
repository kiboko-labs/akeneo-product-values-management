<?php

namespace Kiboko\Component\AkeneoProductValues\Config\Specification;

use Kiboko\Component\AkeneoProductValues\CodeGenerator\ClassCodeGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ConstantAwareCodeGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\InterfaceCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\StringConstantCodeGenerator;

trait ConstantAwareSpecificationTrait
{
    /**
     * @var ConstantAwareCodeGeneratorInterface[]
     */
    private $constantGenerators;

    /**
     * @param ConstantAwareCodeGeneratorInterface[] $generators
     */
    public function setConstantGenerator(array $generators): void
    {
        foreach ($generators as $generator) {
            $this->addConstantGenerator($generator);
        }
    }

    /**
     * @param ConstantAwareCodeGeneratorInterface $generator
     */
    public function addConstantGenerator(ConstantAwareCodeGeneratorInterface $generator): void
    {
        $this->constantGenerators[] = $generator;
    }

    /**
     * @param ConstantAwareCodeGeneratorInterface $generator
     */
    public function removeConstantGenerator(ConstantAwareCodeGeneratorInterface $generator): void
    {
        $key = array_search($generator);
        if ($key === false) {
            return;
        }

        unset($this->constantGenerators[$key]);
    }

    /**
     * @param callable $filter
     *
     * @return InterfaceCodeGenerator[]
     */
    public function findConstantGenerators(callable $filter): array
    {
        return array_filter(
            $this->constants,
            $filter
        );
    }

    /**
     * @return InterfaceCodeGenerator[]
     */
    public function getConstantGenerators(): array
    {
        return $this->constantGenerators;
    }

    /**
     * @param ClassCodeGeneratorInterface $class
     * @param array $values
     */
    public function build(ClassCodeGeneratorInterface $class, array $values): void
    {
        foreach ($values as $code => $value) {
            $class->addConstantCodeGenerator(
                new StringConstantCodeGenerator($class, $code, $value)
            );
        }
    }
}
