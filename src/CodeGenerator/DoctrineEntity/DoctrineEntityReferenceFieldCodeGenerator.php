<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorInterface;
use PhpParser\Builder;
use PhpParser\BuilderFactory;
use PhpParser\Node;

class DoctrineEntityReferenceFieldCodeGenerator implements Builder
{
    /**
     * @var string
     */
    private $fieldName;

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
     * ProductValueScalarFieldCodeGenerator constructor.
     * @param string $fieldName
     * @param string $className
     * @param string $namespace
     * @param AnnotationGeneratorInterface[] $doctrineAnnotationGenerators
     */
    public function __construct($fieldName, $className, $namespace, $doctrineAnnotationGenerators)
    {
        $this->fieldName = $fieldName;
        $this->className = $className;
        $this->namespace = $namespace;
        $this->doctrineAnnotationGenerators = $doctrineAnnotationGenerators;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @param string $fieldName
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
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
     * @return Node\Stmt\Property
     */
    public function getNode()
    {
        $factory = new BuilderFactory();

        $root = $factory->property($this->fieldName)
            ->makePrivate()
            ->setDocComment($this->compileDocComment())
        ;

        return $root->getNode();
    }

    /**
     * @return string
     */
    protected function compileDocComment()
    {
        $annotations = $this->prepareAnnotations();

        array_walk($annotations, function(&$current) {
            $current = '     * ' . $current;
        });

        return '/**' . PHP_EOL
            .implode(PHP_EOL, $annotations) . PHP_EOL
            .'     */';
    }

    /**
     * @return array
     */
    protected function prepareAnnotations()
    {
        $annotations = [
            '@param '.$this->className,
        ];

        foreach ($this->doctrineAnnotationGenerators as $generator) {
            $annotations[] = $generator->getAnnotation();
        }

        return $annotations;
    }
}
