<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationSerializer;
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
    private $name;

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
     * @var bool
     */
    private $useDefault;

    /**
     * @var Node
     */
    private $default;

    /**
     * ProductValueScalarFieldCodeGenerator constructor.
     * @param string $fieldName
     * @param string $name
     * @param string|null $namespace
     * @param bool $nullable
     * @param bool $useStrictTyping
     * @param bool $useDefault
     * @param Node $default
     */
    public function __construct(
        $fieldName,
        $name,
        $namespace = null,
        $nullable = false,
        $useStrictTyping = true,
        $useDefault = false,
        Node $default = null
    ) {
        $this->fieldName = $fieldName;
        $this->name = $name;
        $this->namespace = $namespace;
        $this->nullable = $nullable;
        $this->useStrictTyping = $useStrictTyping;
        $this->useDefault = $useDefault;
        $this->default = $default;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
            if ($this->nullable === true) {
                $param->setTypeHint('?\\' . $this->namespace . '\\' . $this->name);
            } else {
                $param->setTypeHint('?\\' . $this->namespace . '\\' . $this->name);
            }
        }

        if ($this->useDefault === true) {
            $param->setDefault($this->default);
        }

        $root = $factory->method('set'.$this->camelize($this->fieldName))
            ->makePublic()
            ->setDocComment($this->compileDocComment())
            ->addParam($param)
            ->addStmt(
                $assign = new Node\Expr\Assign(
                    new Node\Expr\PropertyFetch(new Node\Expr\Variable('this'), $this->fieldName),
                    new Node\Expr\Variable($this->fieldName)
                )
            )
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
        return '/**' . PHP_EOL
        .'     * @param \\'.$this->namespace.'\\'.$this->name . ' $' . $this->fieldName . PHP_EOL
        .'     */';
    }
}
