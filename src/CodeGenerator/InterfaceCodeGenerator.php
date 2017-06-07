<?php

declare(strict_types=1);

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use Kiboko\Component\AkeneoProductValues\CodeContext\ClassContext;
use Kiboko\Component\AkeneoProductValues\Helper\ClassName;
use PhpParser\BuilderFactory;
use PhpParser\Node;

class InterfaceCodeGenerator implements ClassCodeGeneratorInterface
{
    /**
     * @var FileCodeGenerator
     */
    private $parentGenerator;

    /**
     * @var ClassContext
     */
    private $classContext;

    /**
     * @var ConstantCodeGeneratorInterface[]
     */
    private $constantCodeGenerators;

    /**
     * @var MethodCodeGenerator[]
     */
    private $methodCodeGenerators;

    /**
     * ClassCodeGenerator constructor.
     *
     * @param FileCodeGenerator $parentGenerator
     * @param ClassContext $classContext
     */
    public function __construct(
        FileCodeGenerator $parentGenerator,
        ClassContext $classContext
    ) {
        $parentGenerator->addChild($this);

        $this->parentGenerator = $parentGenerator;
        $this->classContext = $classContext;
        $this->constantCodeGenerators = [];
        $this->methodCodeGenerators = [];
    }

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

    /**
     * @return Node
     */
    public function getNode()
    {
        $factory = new BuilderFactory();

        $root = $factory->interface(ClassName::extractClass($this->classContext->getClassName()));

        if ($parent = $this->classContext->getParentClass()) {
            if ($parent->getAlias() !== null) {
                $root->extend($parent->getAlias());
            } else {
                $root->extend(ClassName::extractClass($parent->getClassName()));
            }
        }

        foreach ($this->constantCodeGenerators as $constantCodeGenerator) {
            var_dump($constantCodeGenerator);
            $root->addStmt($constantCodeGenerator);
        }

        foreach ($this->methodCodeGenerators as $methodCodeGenerator) {
            $root->addStmt($methodCodeGenerator);
        }

        return $root->getNode();
    }
}
