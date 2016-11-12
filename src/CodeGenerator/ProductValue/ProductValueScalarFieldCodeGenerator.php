<?php


namespace Kiboko\Component\AkeneoProductValues\CodeGenerator\ProductValue;

use PhpParser\Builder;
use PhpParser\BuilderFactory;
use PhpParser\Node;

class ProductValueScalarFieldCodeGenerator implements Builder
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
     * ProductValueScalarFieldCodeGenerator constructor.
     * @param string $fieldName
     * @param string $typeHint
     */
    public function __construct($fieldName, $typeHint)
    {
        $this->fieldName = $fieldName;
        $this->typeHint = $typeHint;
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
        return [
            '@param '.$this->phpType,
            $this->prepareDoctrineColumnAnnotation(),
        ];
    }
}
