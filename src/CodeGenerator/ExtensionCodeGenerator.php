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
     * @return Node\Stmt[]
     */
    public function getLoadMethodStatements()
    {
        return $this->loadMethodStatements;
    }

    /**
     * @param Node\Stmt $statement
     */
    public function addLoadMethodStatements(Node\Stmt $statement)
    {
        $this->loadMethodStatements[] = $statement;
    }

    /**
     * @param Node\Stmt[] $statements
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
        foreach ($this->useStatements as $statement) {
            $root->addStmt(
                $factory->use($statement)
            );
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

        $method->addStmt(
            new Node\Expr\Assign(
                new Node\Expr\Variable('loader'),
                new Node\Expr\New_(
                    new Node\Name('Loader\\YamlFileLoader'),
                    [
                        new Node\Expr\Variable('container'),
                        new Node\Expr\New_(
                            new Node\Name('FileLocator'),
                            [
                                new Node\Expr\BinaryOp\Concat(
                                    new Node\Scalar\MagicConst\Dir(),
                                    new Node\Scalar\String_('/../Resources/config')
                                )
                            ]
                        )
                    ]
                )
            )
        );

        $method->addStmt(
            new Node\Expr\MethodCall(
                new Node\Expr\Variable('loader'),
                'load',
                [
                    new Node\Scalar\String_('services.yml')
                ]
            )
        );

        $method->addStmts($this->loadMethodStatements);

        return $method;
    }
}
