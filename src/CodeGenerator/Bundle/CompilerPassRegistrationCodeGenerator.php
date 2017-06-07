<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator\Bundle;

use Kiboko\Component\AkeneoProductValues\CodeContext\ClassReferenceContext;
use Kiboko\Component\AkeneoProductValues\Helper\ClassName;
use PhpParser\Builder;
use PhpParser\Node;

class CompilerPassRegistrationCodeGenerator implements Builder
{
    /**
     * @var string
     */
    private $variableName;

    /**
     * @var ClassReferenceContext
     */
    private $class;

    /**
     * @var Node\Stmt[]
     */
    private $parameterExpressions;

    /**
     * @param ClassReferenceContext $class
     * @param Node\Expr[] $parameterExpressions
     * @param string $variableName
     */
    public function __construct(
        ClassReferenceContext $class,
        array $parameterExpressions = [],
        $variableName = 'container'
    ) {
        $this->variableName = $variableName;
        $this->class = $class;
        $this->parameterExpressions = $parameterExpressions;
    }

    /**
     * @return string
     */
    public function getVariableName()
    {
        return $this->variableName;
    }

    /**
     * @param string $variableName
     */
    public function setVariableName(string $variableName)
    {
        $this->variableName = $variableName;
    }

    /**
     * @return ClassReferenceContext
     */
    public function getClass(): ClassReferenceContext
    {
        return $this->class;
    }

    /**
     * @param ClassReferenceContext $class
     */
    public function setClass(ClassReferenceContext $class)
    {
        $this->class = $class;
    }

    /**
     * @return Node\Stmt[]
     */
    public function getParameterExpressions()
    {
        return $this->parameterExpressions;
    }

    /**
     * @param Node\Stmt $parameterExpression
     */
    public function addParameterExpression($parameterExpression)
    {
        $this->parameterExpressions[] = $parameterExpression;
    }

    /**
     * @param Node\Stmt[] $parameterExpressions
     */
    public function setParameterExpressions(array $parameterExpressions)
    {
        $this->parameterExpressions = $parameterExpressions;
    }

    /**
     * @return Node
     */
    public function getNode()
    {
        return new Node\Expr\MethodCall(
            new Node\Expr\Variable($this->variableName),
            'addCompilerPass',
            [
                new Node\Expr\New_(
                    ClassName::buildNameNode($this->class),
                    [],
                    $this->parameterExpressions
                )
            ]
        );
    }
}
