<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator\FormType;

use PhpParser\Builder;
use PhpParser\BuilderFactory;
use PhpParser\Node;

class FormTypeCodeGenerator implements Builder
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $parentClassName;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $formName;

    /**
     * @var string[]
     */
    private $useStatements;

    /**
     * @var Node\Stmt[]
     */
    private $buildFormMethodStatements;

    /**
     * @var Node\Stmt[]
     */
    private $defaultOptionsMethodStatements;

    /**
     * @param string $name
     * @param string $namespace
     * @param string $formName
     * @param array $useStatements
     * @param string $parentClassName
     * @param array $implementedInterfaces
     */
    public function __construct(
        $name,
        $namespace,
        $formName,
        array $useStatements = [],
        $parentClassName,
        array $implementedInterfaces = []
    ) {
        $this->name = $name;
        $this->namespace = $namespace;
        $this->formName = $formName;
        $this->useStatements = $useStatements + [
            'Pim\\Bundle\\CustomEntityBundle\\Form\\Type\\CustomEntityType',
            'Pim\\Bundle\\EnrichBundle\\Form\\Subscriber\\DisableFieldSubscriber',
            'Symfony\\Component\\Form\\FormBuilderInterface',
            'Symfony\\Component\\OptionsResolver\\OptionsResolverInterface',
        ];
        $this->buildFormMethodStatements = [];
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
    public function getFQN()
    {
        return $this->namespace.'\\'.$this->name;
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
    public function getBuildFormMethodStatements()
    {
        return $this->buildFormMethodStatements;
    }

    /**
     * @param Node $statement
     */
    public function addBuildFormMethodStatements(Node $statement)
    {
        $this->buildFormMethodStatements[] = $statement;
    }

    /**
     * @param Node[] $statements
     */
    public function setBuildFormMethodStatements(array $statements)
    {
        $this->buildFormMethodStatements = $statements;
    }

    /**
     * @return Node[]
     */
    public function getDefaultOptionsMethodStatements()
    {
        return $this->defaultOptionsMethodStatements;
    }

    /**
     * @param Node $statement
     */
    public function addDefaultOptionsMethodStatements(Node $statement)
    {
        $this->defaultOptionsMethodStatements[] = $statement;
    }

    /**
     * @param Node[] $statements
     */
    public function setDefaultOptionsMethodStatements(array $statements)
    {
        $this->defaultOptionsMethodStatements = $statements;
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
            $factory->class($this->name)
                ->extend('Bundle')
                ->addStmts(
                    [
                        $this->generateBuildFormMethod($factory),
                        $this->generateDefaultOptionsMethod($factory),
                        $this->generateGetNameMethod($factory),
                    ]
                )
        );

        return $root->getNode();
    }

    private function generateBuildFormMethod(BuilderFactory $factory)
    {
        $method = $factory->method('buildForm')
            ->makePublic()
            ->addParam($factory->param('builder')->setTypeHint('FormBuilderInterface'))
            ->addParam($factory->param('options')->setTypeHint('array'))
        ;

        $method->addStmts($this->buildFormMethodStatements);

        return $method;
    }

    private function generateDefaultOptionsMethod(BuilderFactory $factory)
    {
        $method = $factory->method('defaultOptions')
            ->makePublic()
            ->addParam($factory->param('resolver')->setTypeHint('OptionsResolverInterface'))
        ;

        $method->addStmts($this->defaultOptionsMethodStatements);

        return $method;
    }

    private function generateGetNameMethod(BuilderFactory $factory)
    {
        $method = $factory->method('getName')
            ->makePublic()
        ;

        $method->addStmt(
            new Node\Stmt\Return_(
                new Node\Scalar\String_($this->formName)
            )
        );

        return $method;
    }
}
