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
    private $exposedMappedFields;

    /**
     * @param string $className
     * @param string $namespace
     * @param Builder[] $exposedMappedFields
     */
    public function __construct($className, $namespace, array $exposedMappedFields)
    {
        $this->className = $className;
        $this->namespace = $namespace;
        $this->useStatements = [
            'Symfony\\Component\\DependencyInjection\\ContainerBuilder',
            'Symfony\\Component\\HttpKernel\\Bundle\\Bundle',
        ];
        $this->exposedMappedFields = [];
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
     */
    public function addUseStatement($useStatement)
    {
        $this->useStatements[] = $useStatement;
    }

    /**
     * @return \PhpParser\Builder[]
     */
    public function getExposedMappedFields()
    {
        return $this->exposedMappedFields;
    }

    /**
     * @param \PhpParser\Builder $exposedMappedField
     */
    public function addExposedMappedField($exposedMappedField)
    {
        $this->exposedMappedFields[] = $exposedMappedField;
    }

    /**
     * @param \PhpParser\Builder[] $exposedMappedFields
     */
    public function setExposedMappedFields(array $exposedMappedFields)
    {
        $this->exposedMappedFields = $exposedMappedFields;
    }

    /**
     * @return Node
     */
    public function getNode()
    {
        $factory = new BuilderFactory();

        $root = $factory->namespace($this->namespace);
        sort($this->useStatements);
        foreach ($this->useStatements as $statement) {
            $root->addStmt(
                $factory->use($statement)
            );
        }

        $root->addStmt(
            $class = $factory->class($this->className)
                ->extend('Bundle')
        );

        foreach ($this->exposedMappedFields as $fieldCodeGenerator) {
            $class->addStmt($fieldCodeGenerator);
        }

        return $root->getNode();
    }
}
