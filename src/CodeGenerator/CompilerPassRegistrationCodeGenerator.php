<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use PhpParser\Builder;
use PhpParser\Node;

class CompilerPassRegistrationCodeGenerator implements Builder
{
    /**
     * @var string
     */
    private $variableName;

    /**
     * @var string
     */
    private $className;

    /**
     * @var Node\Stmt[]
     */
    private $parameterExpressions;

    /**
     * @param string $variableName
     * @param string $className
     * @param Node\Expr[] $parameterExpressions
     */
    public function __construct($className, array $parameterExpressions = [], $variableName = 'container')
    {
        $this->variableName = $variableName;
        $this->className = $className;
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
    public function setVariableName($variableName)
    {
        $this->variableName = $variableName;
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
                    new Node\Name\FullyQualified($this->className),
                    [],
                    $this->parameterExpressions
                )
            ]
        );
    }
}
