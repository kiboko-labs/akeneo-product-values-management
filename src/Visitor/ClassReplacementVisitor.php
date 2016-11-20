<?php

namespace Kiboko\Component\AkeneoProductValues\Visitor;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class ClassReplacementVisitor extends NodeVisitorAbstract
{
    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $classFQN;

    /**
     * @var Node\Stmt\Class_
     */
    private $classDeclaration;

    /**
     * @return string
     */
    public function getClassFQN()
    {
        return $this->classFQN;
    }

    /**
     * @param string $classFQN
     */
    public function setClassFQN($classFQN)
    {
        $this->classFQN = $classFQN;
    }

    /**
     * @return Node\Stmt\Class_
     */
    public function getClassDeclaration()
    {
        return $this->classDeclaration;
    }

    /**
     * @param Node\Stmt\Class_ $classDeclaration
     */
    public function setClassDeclaration($classDeclaration)
    {
        $this->classDeclaration = $classDeclaration;
    }

    /**
     * @param array $nodes
     * @throws \RuntimeException
     * @return null|Node[]
     */
    public function beforeTraverse(array $nodes)
    {
        if ($this->classFQN === null || $this->classDeclaration === null) {
            throw new \RuntimeException('The class name and class declaration were not previously declared.');
        }
    }

    /**
     * @param Node $node
     * @throws \RuntimeException
     * @return null|Node
     */
    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->resetState($node->name);
            return;
        }
        if (!$node instanceof Node\Stmt\Class_) {
            return;
        }

        if ($node instanceof Node\Name\FullyQualified) {
            $classFQN = $node->name->toString();
        } else if ($node instanceof Node\Name\Relative) {
            $classFQN = $this->namespace . '\\' . $node->name->toString();
        } else if ($node->name instanceof Node\Name) {
            $classFQN = $this->namespace.'\\'.$node->name->toString();
        } else {
            $classFQN = $this->namespace.'\\'.$node->name;
        }

        if ($classFQN !== $this->classFQN) {
            return;
        }

        return $this->classDeclaration;
    }

    /**
     * @param Node\Name|null $namespace
     */
    private function resetState(Node\Name $namespace = null)
    {
        $this->namespace = $namespace->toString();
    }
}
