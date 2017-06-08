<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use Kiboko\Component\AkeneoProductValues\CodeContext\ClassReferenceContext;
use PhpParser\Builder;
use PhpParser\BuilderFactory;
use PhpParser\Node;

class FileCodeGenerator implements Builder
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var ClassReferenceContext[]
     */
    private $usedReferences;

    /**
     * @var Builder[]
     */
    private $children;

    /**
     * FileCodeGenerator constructor.
     * @param string $path
     * @param string $namespace
     * @param ClassReferenceContext[] $usedReferences
     */
    public function __construct(string $path, string $namespace, array $usedReferences = [])
    {
        $this->path = $path;
        $this->namespace = $namespace;
        $this->usedReferences = $usedReferences;
        $this->children = [];
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @param $reference $reference
     */
    public function addUsedReference(ClassReferenceContext $reference): void
    {
        $this->usedReferences[] = $reference;
    }

    /**
     * @param ClassReferenceContext $reference
     */
    public function removeUsedReference(ClassReferenceContext $reference): void
    {
        $this->usedReferences = array_filter($this->usedReferences, function(ClassReferenceContext $item) use($reference) {
            if ($item->getName() !== $reference->getName()) {
                return true;
            }

            if ($item->getAlias() !== $reference->getAlias()) {
                return true;
            }

            return false;
        });
    }

    /**
     * @return ClassReferenceContext[]
     */
    public function getUsedReferences(): array
    {
        return $this->usedReferences;
    }

    /**
     * @return Builder[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param Builder $child
     */
    public function addChild(Builder $child)
    {
        $this->children[] = $child;
    }

    /**
     * @param Builder[] $children
     */
    public function setChildren(array $children)
    {
        $this->children = $children;
    }

    /**
     * @return Node
     */
    public function getNode()
    {
        $factory = new BuilderFactory();

        $root = $factory->namespace($this->namespace);
        ksort($this->usedReferences);
        foreach ($this->usedReferences as $classReference => $alias) {
            $use = $factory->use($classReference);
            if ($alias !== null) {
                $use->as($alias);
            }
            $root->addStmt($use);
        }

        $root->addStmts($this->children);

        return $root->getNode();
    }
}
