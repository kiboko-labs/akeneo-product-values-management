<?php

namespace Kiboko\Component\AkeneoProductValues\Visitor\Bundle;

use Kiboko\Component\AkeneoProductValues\CodeGenerator\CompilerPassRegistrationCodeGenerator;
use PhpParser\Builder;
use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class BundleBuildMethodVisitor extends NodeVisitorAbstract
{
    /**
     * @var array|\PhpParser\Builder[]
     */
    private $compilerPassBuilders;

    /**
     * @var bool
     */
    private $active;

    /**
     * @param Builder[] $compilerPassBuilders
     */
    public function __construct(array $compilerPassBuilders)
    {
        $this->compilerPassBuilders = $compilerPassBuilders;
        $this->active = false;
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Class_) {
            if ($node->extends->toString() === 'Symfony\Component\HttpKernel\Bundle\Bundle') {
                $this->active = true;
            }
            return;
        }

        if (!$this->active) {
            return;
        }

        if (!$node instanceof Node\Stmt\ClassMethod) {
            return;
        }

        if ($node->name !== 'build') {
            return;
        }

        $argumentName = $node->params[0]->name;
        $this->appendCompilerPassRegistrations($argumentName, $node);
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Class_) {
            $this->active = false;
        }
    }

    /**
     * @param string $argumentName
     * @param Node\Stmt\ClassMethod $node
     */
    private function appendCompilerPassRegistrations($argumentName, Node\Stmt\ClassMethod $node)
    {
        $builders = $this->compilerPassBuilders;
        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof Node\Expr\MethodCall) {
                if ($stmt->var->name !== $argumentName) {
                    continue;
                }

                if ($stmt->name !== 'addCompilerPass') {
                    continue;
                }

                /** @var CompilerPassRegistrationCodeGenerator $builder */
                foreach ($builders as $index => $builder) {
                    $classFQN = $stmt->args[0]->value->class->toString();
                    if ($builder->getClassName() !== $classFQN) {
                        continue;
                    }

                    if (count($builder->getParameterExpressions()) !== 0) {
                        // TODO: check parameter list
                        continue;
                    }

                    if (count($stmt->args[0]->value->args) !== 0) {
                        // TODO: check parameter list
                        continue;
                    }

                    unset($builders[$index]);
                }
            }
        }

        if (count($builders) <= 0) {
            return;
        }

        $node->stmts[] = $comment = new Node\Stmt\Nop();
        $comment->setAttribute('comments',
            [
                new Comment('// Added code on ' . (new \DateTimeImmutable('now'))->format('r')),
            ]
        );

        /** @var CompilerPassRegistrationCodeGenerator $builder */
        foreach ($builders as $builder) {
            $builder->setVariableName($argumentName);

            $node->stmts[] = $builder->getNode();
        }
    }
}
