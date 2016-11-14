<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use PhpParser\Builder;
use PhpParser\BuilderFactory;
use PhpParser\Node;

class ExtensionCodeGenerator implements Builder
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
    private $loadMethodStatements;

    /**
     * @param string $className
     * @param string $namespace
     */
    public function __construct($className, $namespace)
    {
        $this->className = $className;
        $this->namespace = $namespace;
        $this->useStatements = [
            'Symfony\\Component\\Config\\FileLocator',
            'Symfony\\Component\\DependencyInjection\\ContainerBuilder',
            'Symfony\\Component\\DependencyInjection\\Extension\\Extension',
            'Symfony\\Component\\DependencyInjection\\Loader',
        ];
        $this->loadMethodStatements = [];
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
     * @param \string[] $useStatements
     */
    public function setUseStatements(array $useStatements)
    {
        $this->useStatements = $useStatements;
    }

    /**
     * @return Node[]
     */
    public function getLoadMethodStatements()
    {
        return $this->loadMethodStatements;
    }

    /**
     * @param Node $statement
     */
    public function addLoadMethodStatement(Node $statement)
    {
        $this->loadMethodStatements[] = $statement;
    }

    /**
     * @param Node[] $statements
     */
    public function setLoadMethodStatements(array $statements)
    {
        $this->loadMethodStatements = $statements;
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
                ->extend('Extension')
                ->addStmt(
                    $this->generateLoadMethod($factory)
                )
        );

        return $root->getNode();
    }

    private function generateLoadMethod(BuilderFactory $factory)
    {
        $method = $factory->method('load')
            ->makePublic()
            ->addParam($factory->param('configs')->setTypeHint('array'))
            ->addParam($factory->param('container')->setTypeHint('ContainerBuilder'))
        ;

        $method->addStmts($this->loadMethodStatements);

        return $method;
    }
}
