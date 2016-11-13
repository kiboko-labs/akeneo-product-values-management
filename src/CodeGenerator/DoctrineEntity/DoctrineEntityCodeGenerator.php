<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorInterface;
use PhpParser\Builder;
use PhpParser\BuilderFactory;
use PhpParser\Node;

class DoctrineEntityCodeGenerator implements Builder
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var AnnotationGeneratorInterface[]
     */
    private $doctrineAnnotationGenerators;

    /**
     * @var DoctrineEntityScalarFieldCodeGenerator[]
     */
    private $fieldCodeGenerators;

    /**
     * ProductValueScalarFieldCodeGenerator constructor.
     * @param string $className
     * @param string $namespace
     * @param AnnotationGeneratorInterface[] $doctrineAnnotationGenerators
     * @param DoctrineEntityScalarFieldCodeGenerator[] $fieldCodeGenerators
     */
    public function __construct(
        $className,
        $namespace,
        array $doctrineAnnotationGenerators = [],
        array $fieldCodeGenerators = []
    ) {
        $this->className = $className;
        $this->namespace = $namespace;
        $this->doctrineAnnotationGenerators = $doctrineAnnotationGenerators;
        $this->fieldCodeGenerators = $fieldCodeGenerators;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName($className)
    {
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return Node
     */
    public function getNode()
    {
        $factory = new BuilderFactory();

        $root = $factory->namespace($this->namespace);

        $class = $factory->class($this->className)
            ->setDocComment($this->compileDocComment());

        foreach ($this->fieldCodeGenerators as $field) {
            $class->addStmt($field->getNode());
        }

        $root->addStmt($class);

        return $root->getNode();
    }

    /**
     * @return string
     */
    protected function compileDocComment()
    {
        $annotations = $this->prepareAnnotations();

        return '/**' . PHP_EOL
        .array_walk($annotations, function($current) {return ' * ' . $current . PHP_EOL;})
        .' */';
    }

    /**
     * @return array
     */
    protected function prepareAnnotations()
    {
        $annotations = [];
        foreach ($this->doctrineAnnotationGenerators as $generator) {
            $annotations[] = $generator->getAnnotation();
        }

        return $annotations;
    }
}
