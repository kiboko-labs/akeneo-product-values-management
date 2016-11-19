<?php

namespace Kiboko\Component\AkeneoProductValues\Visitor;

use PhpParser\Builder;
use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class CodeGeneratorApplierVisitor extends NodeVisitorAbstract
{
    /**
     * @var Node\Stmt\Const_[]
     */
    private $consts;

    /**
     * @var Node\Stmt\Property[]
     */
    private $properties;

    /**
     * @var Node\Stmt\ClassMethod[]
     */
    private $methods;

    /**
     * @var Node\Stmt\ClassMethod[]
     */
    private $traitUses;

    /**
     * @var Builder[]
     */
    private $constBuilders;

    /**
     * @var Builder[]
     */
    private $propertyBuilders;

    /**
     * @var Builder[]
     */
    private $methodBuilders;

    /**
     * @var Builder[]
     */
    private $traitUseBuilders;

    /**
     * @param Node $node
     * @return null
     */
    public function enterNode(Node $node)
    {
        if (!$node instanceof Node\Stmt\Class_) {
            return;
        }

        $this->resetState($node);

        $factory = new BuilderFactory();

        $class = $factory->class($node->name);
        if ($node->isAbstract()) {
            $class->makeAbstract();
        }
        if ($node->isFinal()) {
            $class->makeFinal();
        }
        $class->implement(...$node->implements);
        $class->extend($node->extends);

        $this->applyUseCodeGenerators($class);
        $this->applyConstCodeGenerators($class);
        $this->applyPropertyCodeGenerators($class);
        $this->applyMethodCodeGenerators($class);

        return $class;
    }

    /**
     * @param Builder $builder
     */
    public function appendUseCodeGenerators(Builder $builder)
    {
        $this->traitUses[] = $builder;
    }

    /**
     * @param Builder $builder
     */
    public function appendConstCodeGenerators(Builder $builder)
    {
        $this->constBuilders[] = $builder;
    }

    /**
     * @param Builder $builder
     */
    public function appendPropertyCodeGenerators(Builder $builder)
    {
        $this->propertyBuilders[] = $builder;
    }

    /**
     * @param Builder $builder
     */
    public function appendMethodCodeGenerators(Builder $builder)
    {
        $this->methodBuilders[] = $builder;
    }

    /**
     * @param Node\Stmt\Class_ $node
     */
    private function resetState(Node\Stmt\Class_ $node)
    {
        $this->traitUses = [];
        $this->consts = [];
        $this->properties = [];
        $this->methods = [];

        foreach ($node->stmts as $childNode) {
            if ($node instanceof Node\Stmt\Const_) {
                $this->consts[$node->name] = $node;
                continue;
            }

            if ($node instanceof Node\Stmt\Property) {
                $this->properties[$node->name] = $node;
                continue;
            }

            if ($node instanceof Node\Stmt\ClassMethod) {
                $this->methods[$node->name] = $node;
                continue;
            }

            if ($node instanceof Node\Stmt\TraitUse) {
                $this->traitUses[$node->name] = $node;
                continue;
            }
        }
    }

    /**
     * @param Builder\Class_ $class
     */
    private function applyUseCodeGenerators(Builder\Class_ $class)
    {
        foreach ($this->traitUseBuilders as $builder) {
            $class->addStmt($builder->getNode());
        }
    }

    /**
     * @param Builder\Class_ $class
     */
    private function applyConstCodeGenerators(Builder\Class_ $class)
    {
        foreach ($this->constBuilders as $builder) {
            $class->addStmt($builder->getNode());
        }
    }

    /**
     * @param Builder\Class_ $class
     */
    private function applyPropertyCodeGenerators(Builder\Class_ $class)
    {
        foreach ($this->propertyBuilders as $builder) {
            $class->addStmt($builder->getNode());
        }
    }

    /**
     * @param Builder\Class_ $class
     */
    private function applyMethodCodeGenerators(Builder\Class_ $class)
    {
        foreach ($this->methodBuilders as $builder) {
            $class->addStmt($builder->getNode());
        }
    }
}
