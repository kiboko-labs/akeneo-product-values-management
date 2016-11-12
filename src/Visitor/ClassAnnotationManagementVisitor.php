<?php

namespace Kiboko\Component\AkeneoProductValues\Visitor;

use Doctrine\Common\Annotations\DocParser;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorInterface;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class AnnotationManagementVisitor extends NodeVisitorAbstract
{
    /**
     * @var string
     */
    private $classFQN;

    /**
     * @var AnnotationGeneratorInterface[]
     */
    private $annotaitonGenerators;

    /**
     * @var string
     */
    private $namespace;

    /**
     * AnnotationManagementVisitor constructor.
     * @param string $classFQN
     * @param AnnotationGeneratorInterface[] $annotaitonGenerators
     */
    public function __construct($classFQN, array $annotaitonGenerators)
    {
        $this->classFQN = $classFQN;
        $this->annotaitonGenerators = $annotaitonGenerators;
    }

    /**
     * @param Node[] $nodes
     * @return null
     */
    public function beforeTraverse(array $nodes)
    {
        $this->namespace = null;
    }

    /**
     * @param Node $node
     * @return null
     */
    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->resetState($node->name);
        }

        if (!$node instanceof Node\Stmt\Class_) {
            return;
        }

        if ($node instanceof Node\Name\FullyQualified) {
            $classFQN = $node->name->toString();
        } else {
            $classFQN = $this->namespace . '\\' . $node->name->toString();
        }

        if ($classFQN !== $this->classFQN) {
            return;
        }

        $lexer = new DocParser();
        $tokens = $lexer->parse($node->getDocComment());

        var_dump($tokens);
    }

    /**
     * @param Node\Name|null $namespace
     */
    private function resetState(Node\Name $namespace = null)
    {
        $this->namespace = $namespace->toString();
    }
}
