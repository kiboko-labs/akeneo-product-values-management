<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationSerializer;
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
     * @param string|null $namespace
     * @param AnnotationGeneratorInterface[] $doctrineAnnotationGenerators
     */
    public function __construct($fieldName, $className, $namespace = null, $doctrineAnnotationGenerators = [])
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
        $annotationSerializer = new AnnotationSerializer();

        $serialized = '/**' . PHP_EOL
        .' * @var \\'.$this->namespace.'\\'.$this->className . PHP_EOL
        .' *' . PHP_EOL;
        foreach ($this->doctrineAnnotationGenerators as $annotation) {
            $serialized .= $annotationSerializer->serialize($annotation) . PHP_EOL;
        }
        return $serialized . ' */';
    }
}
