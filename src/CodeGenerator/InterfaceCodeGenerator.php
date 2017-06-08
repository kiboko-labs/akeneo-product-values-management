<?php

declare(strict_types=1);

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use Kiboko\Component\AkeneoProductValues\CodeContext\ClassContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\ContextVisitorInterface;
use Kiboko\Component\AkeneoProductValues\Helper\ClassName;
use PhpParser\Builder\Interface_;
use PhpParser\BuilderFactory;
use PhpParser\Node;

class InterfaceCodeGenerator implements InterfaceCodeGeneratorInterface
{
    use ConstantAwareCodeGeneratorTrait;
    use MethodAwareCodeGeneratorTrait;
    use AnnotationAwareCodeGeneratorTrait;
    use ContextAwareCodeGeneratorTrait;

    /**
     * @var FileCodeGenerator
     */
    private $parentGenerator;

    /**
     * ClassCodeGenerator constructor.
     *
     * @param FileCodeGenerator $parentGenerator
     * @param ClassContext $context
     */
    public function __construct(
        FileCodeGenerator $parentGenerator,
        ClassContext $context
    ) {
        $parentGenerator->addChild($this);

        $this->parentGenerator = $parentGenerator;
        $this->context = $context;
    }

    /**
     * @return Node
     */
    public function getNode()
    {
        $factory = new BuilderFactory();

        /** @var Interface_ $root */
        $root = $factory->interface(ClassName::extractClass($this->context->getName()))
            ->setDocComment($this->compileDocComment());

        foreach ($this->context->getImplementedInterfaces() as $implementedInterface) {
            if ($implementedInterface->getAlias()) {
                $root->extend($implementedInterface->getAlias());
            } else {
                $root->extend(ClassName::extractClass($implementedInterface->getName()));
            }
        }

        foreach ($this->constantCodeGenerators as $constantCodeGenerator) {
            $root->addStmt($constantCodeGenerator);
        }

        foreach ($this->methodCodeGenerators as $methodCodeGenerator) {
            $root->addStmt($methodCodeGenerator);
        }

        return $root->getNode();
    }
}
