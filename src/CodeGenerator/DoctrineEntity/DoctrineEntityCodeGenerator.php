<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationSerializer;
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
     * @var string[]
     */
    private $useStatements;

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
        $this->useStatements = [];
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
    public function getFQN()
    {
        return $this->namespace.'\\'.$this->className;
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
     * @return \string[]
     */
    public function getUseStatements()
    {
        return $this->useStatements;
    }

    /**
     * @param \string $useStatement
     */
    public function addUseStatement($useStatement)
    {
        $this->useStatements[] = $useStatement;
    }

    /**
     * @param \string[] $useStatements
     */
    public function setUseStatements(array $useStatements)
    {
        $this->useStatements = $useStatements;
    }

    /**
     * @return Node
     */
    public function getNode()
    {
        $factory = new BuilderFactory();

        $root = $factory->namespace($this->namespace);
        sort($this->useStatements);
        foreach ($this->useStatements as $alias => $statement) {
            $use = $factory->use($statement);
            if (!is_numeric($alias)) {
                $use->as($alias);
            }
            $root->addStmt($use);
        }

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
        $annotationSerializer = new AnnotationSerializer();

        return '/**' . PHP_EOL
        .array_walk(
            $this->doctrineAnnotationGenerators,
            function(AnnotationGeneratorInterface $current) use($annotationSerializer) {
                return $annotationSerializer->serialize($current) . PHP_EOL;
            }
        )
        .' */';
    }
}
