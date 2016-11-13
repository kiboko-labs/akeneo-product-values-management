<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity;

use PhpParser\Builder;
use PhpParser\BuilderFactory;
use PhpParser\Node;

class DoctrineEntityScalarFieldGetMethodCodeGenerator implements Builder
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
     * @var bool
     */
    private $nullable;

    /**
     * @var bool
     */
    private $useStrictTyping;

    /**
     * ProductValueScalarFieldCodeGenerator constructor.
     * @param string $fieldName
     * @param string $typeHint
     * @param bool $nullable
     * @param bool $useStrictTyping
     */
    public function __construct($fieldName, $typeHint, $nullable = false, $useStrictTyping = false)
    {
        $this->fieldName = $fieldName;
        $this->typeHint = $typeHint;
        $this->nullable = $nullable;
        $this->useStrictTyping = $useStrictTyping;
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
     * @return Node\Stmt\ClassMethod
     */
    public function getNode()
    {
        $factory = new BuilderFactory();

        $root = $factory->method('get'.$this->camelize($this->fieldName))
            ->makePublic()
            ->setDocComment($this->compileDocComment())
        ;

        if ($this->useStrictTyping === true ||
            !in_array($this->typeHint, ['array', 'callable', 'string', 'int', 'float', 'bool', 'self'])
        ) {
            $root->setReturnType($this->typeHint);
        }

        return $root->getNode();
    }

    /**
     * Camelizes a given string.
     *
     * @param string $string Some string
     *
     * @return string The camelized version of the string
     */
    private function camelize($string)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
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
        return [
            '@return '.$this->typeHint,
        ];
    }
}
