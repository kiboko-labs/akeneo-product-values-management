<?php

namespace Kiboko\Component\AkeneoProductValues\Visitor;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class ClassDiscoveryVisitor extends NodeVisitorAbstract
{
    /**
     * @var string
     */
    private $namespace;

    /**
     * @var Node\Stmt\Class_[]
     */
    private $classes;

    /**
     * @param array $nodes
     * @throws \RuntimeException
     * @return null|Node[]
     */
    public function beforeTraverse(array $nodes)
    {
        $this->namespace = null;
        $this->classes = [];
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

        $this->classes[$classFQN] = $node;
    }

    /**
     * @param Node\Name|null $namespace
     */
    private function resetState(Node\Name $namespace = null)
    {
        $this->namespace = $namespace->toString();
        $this->classes = [];
    }

    /**
     * @return Node\Stmt\Class_[]
     */
    public function dump()
    {
        return $this->classes;
    }
}
