<?php

declare(strict_types=1);

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use Kiboko\Component\AkeneoProductValues\CodeContext\ClassContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\ClassReferenceContext;
use Kiboko\Component\AkeneoProductValues\Helper\ClassName;
use PhpParser\BuilderFactory;
use PhpParser\Node;

class ClassCodeGenerator implements ClassCodeGeneratorInterface
{
    use ConstantAwareCodeGeneratorTrait;
    use MethodAwareCodeGeneratorTrait;
    use PropertyAwareCodeGeneratorTrait;
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

        $root = $factory->class(ClassName::extractClass($this->context->getName()))
            ->setDocComment($this->compileDocComment());

        if ($parent = $this->context->getParentClass()) {
            if ($parent->getAlias() !== null) {
                $root->extend($parent->getAlias());
            } else {
                $root->extend(ClassName::extractClass($parent->getName()));
            }
        }

        foreach ($this->context->getImplementedInterfaces() as $implementedInterface) {
            if ($implementedInterface->getAlias()) {
                $root->implement($implementedInterface->getAlias());
            } else {
                $root->implement(ClassName::extractClass($implementedInterface->getName()));
            }
        }

        if (count($this->context->getUsedTraits()) > 0) {
            $root->addStmt(
                new Node\Stmt\TraitUse(
                    array_map(function (ClassReferenceContext $item) {
                        if ($item->getAlias()) {
                            return new Node\Name\Relative($item->getAlias());
                        } else {
                            return new Node\Name\Relative(ClassName::extractClass($item->getName()));
                        }
                    }, $this->context->getUsedTraits())
                )
            );
        }

        foreach ($this->constantCodeGenerators as $constantCodeGenerator) {
            $root->addStmt($constantCodeGenerator);
        }

        foreach ($this->propertyCodeGenerators as $propertyCodeGenerator) {
            $root->addStmt($propertyCodeGenerator);
        }

        foreach ($this->methodCodeGenerators as $methodCodeGenerator) {
            $root->addStmt($methodCodeGenerator);
        }

        return $root->getNode();
    }
}
