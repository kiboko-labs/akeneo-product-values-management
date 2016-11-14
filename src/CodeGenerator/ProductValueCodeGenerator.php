<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use PhpParser\Builder;
use PhpParser\BuilderFactory;
use PhpParser\Node;

class ProductValueCodeGenerator implements Builder
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
     * @var Builder[]
     */
    private $internalFields;

    /**
     * @var Builder[]
     */
    private $methods;

    /**
     * @param string $className
     * @param string $namespace
     */
    public function __construct($className, $namespace)
    {
        $this->className = $className;
        $this->namespace = $namespace;
        $this->useStatements = [
            'ORM' => 'Doctrine\\ORM\\Mapping',
            'PimProductValue' => 'Pim\\Component\\Catalog\\Model\\ProductValue',
        ];
        $this->internalFields = [];
        $this->methods = [];
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
     *
     * @return $this
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
     * @param \string|null $alias
     */
    public function addUseStatement($useStatement, $alias = null)
    {
        if ($alias !== null) {
            $this->useStatements[$alias] = $useStatement;
        } else {
            $this->useStatements[] = $useStatement;
        }
    }

    /**
     * @return \PhpParser\Builder[]
     */
    public function getInternalFields()
    {
        return $this->internalFields;
    }

    /**
     * @param \PhpParser\Builder $internalFields
     */
    public function addInternalField($internalFields)
    {
        $this->internalFields[] = $internalFields;
    }

    /**
     * @param \PhpParser\Builder[] $internalFields
     */
    public function setInternalFields(array $internalFields)
    {
        $this->internalFields = $internalFields;
    }

    /**
     * @return \PhpParser\Builder[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param \PhpParser\Builder $method
     */
    public function addMethod($method)
    {
        $this->methods[] = $method;
    }

    /**
     * @param \PhpParser\Builder[] $methods
     */
    public function setMethods(array $methods)
    {
        $this->methods = $methods;
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

        $class = $factory->class($this->className);

        foreach ($this->internalFields as $generator) {
            $class->addStmt($generator);
        }

        foreach ($this->methods as $generator) {
            $class->addStmt($generator);
        }

        $root->addStmt($class);

        return $root->getNode();
    }
}
