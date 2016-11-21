<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationSerializer;
use PhpParser\Builder;
use PhpParser\BuilderFactory;
use PhpParser\Node;

class DoctrineEntityScalarFieldCodeGenerator implements Builder
{
    /**
     * @var string
     */
    private $fieldName;

    /**
     * @var string
     */
    private $typeHint;

    /**
     * @var AnnotationGeneratorInterface[]
     */
    private $doctrineAnnotationGenerators;

    /**
     * ProductValueScalarFieldCodeGenerator constructor.
     * @param string $fieldName
     * @param string $typeHint
     * @param AnnotationGeneratorInterface[] $doctrineAnnotationGenerators
     */
    public function __construct($fieldName, $typeHint, $doctrineAnnotationGenerators)
    {
        $this->fieldName = $fieldName;
        $this->typeHint = $typeHint;
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
    public function getTypeHint()
    {
        return $this->typeHint;
    }

    /**
     * @param string $typeHint
     */
    public function setTypeHint($typeHint)
    {
        $this->typeHint = $typeHint;
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
        .' * @var \\'.$this->typeHint . PHP_EOL
        .' *' . PHP_EOL;
        foreach ($this->doctrineAnnotationGenerators as $annotation) {
            $serialized .= $annotationSerializer->serialize($annotation) . PHP_EOL;
        }
        return $serialized . ' */';
    }
}
