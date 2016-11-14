<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use PhpParser\Builder;
use PhpParser\BuilderFactory;
use PhpParser\Node;

class BundleCodeGenerator implements Builder
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
     * @var Node\Stmt[]
     */
    private $buildMethodStatements;

    /**
     * @param string $className
     * @param string $namespace
     */
    public function __construct($className, $namespace)
    {
        $this->className = $className;
        $this->namespace = $namespace;
        $this->useStatements = [
            'Symfony\\Component\\DependencyInjection\\ContainerBuilder',
            'Symfony\\Component\\HttpKernel\\Bundle\\Bundle',
        ];
        $this->buildMethodStatements = [];
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
     * @return Node[]
     */
    public function getBuildMethodStatements()
    {
        return $this->buildMethodStatements;
    }

    /**
     * @param Node $statement
     */
    public function addBuildMethodStatements(Node $statement)
    {
        $this->buildMethodStatements[] = $statement;
    }

    /**
     * @param Node[] $statements
     */
    public function setBuildMethodStatements(array $statements)
    {
        $this->buildMethodStatements = $statements;
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

        $root->addStmt(
            $factory->class($this->className)
                ->extend('Bundle')
                ->addStmt(
                    $this->generateBuildMethod($factory)
                )
        );

        return $root->getNode();
    }

    private function generateBuildMethod(BuilderFactory $factory)
    {
        $method = $factory->method('build')
            ->makePublic()
            ->addParam($factory->param('container')->setTypeHint('ContainerBuilder'))
        ;

        $method->addStmts($this->buildMethodStatements);

        return $method;
    }
}
