<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity;

use PhpParser\Builder;
use PhpParser\BuilderFactory;
use PhpParser\Node;

class DoctrineEntityReferenceFieldSetMethodCodeGenerator implements Builder
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
     * @param string $className
     * @param string $namespace
     * @param bool $nullable
     * @param bool $useStrictTyping
     */
    public function __construct($fieldName, $className, $namespace, $nullable = false, $useStrictTyping = false)
    {
        $this->fieldName = $fieldName;
        $this->className = $className;
        $this->namespace = $namespace;
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
     * @return Node\Stmt\ClassMethod
     */
    public function getNode()
    {
        $factory = new BuilderFactory();

        $param = $factory->param($this->fieldName);
        if ($this->useStrictTyping === true) {
            $param->setTypeHint($this->className);
        }

        if ($this->nullable === true) {
            $param->setDefault(null);
        }

        $root = $factory->method('set'.$this->camelize($this->fieldName))
            ->makePublic()
            ->setDocComment($this->compileDocComment())
            ->addParam($param)
        ;

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
            '@param '.$this->className . ' $' . $this->fieldName,
        ];
    }
}