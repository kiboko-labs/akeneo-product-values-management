<?php

declare(strict_types=1);

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationSerializer;
use Kiboko\Component\AkeneoProductValues\CodeContext\ClassContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\ClassReferenceContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\ContextVisitorInterface;
use Kiboko\Component\AkeneoProductValues\Helper\ClassName;
use PhpParser\BuilderFactory;
use PhpParser\Node;

class ClassCodeGenerator implements ClassCodeGeneratorInterface
{
    /**
     * @var FileCodeGenerator
     */
    private $parentGenerator;

    /**
     * @var ClassContext
     */
    private $classContext;

    /**
     * @var ConstantCodeGeneratorInterface[]
     */
    private $constantCodeGenerators;

    /**
     * @var PropertyCodeGenerator[]
     */
    private $propertyCodeGenerators;

    /**
     * @var MethodCodeGenerator[]
     */
    private $methodCodeGenerators;

    /**
     * @var AnnotationGeneratorInterface[]
     */
    private $annotationGenerators;

    /**
     * ClassCodeGenerator constructor.
     *
     * @param FileCodeGenerator $parentGenerator
     * @param ClassContext $classContext
     */
    public function __construct(
        FileCodeGenerator $parentGenerator,
        ClassContext $classContext
    ) {
        $parentGenerator->addChild($this);

        $this->parentGenerator = $parentGenerator;
        $this->classContext = $classContext;
        $this->constantCodeGenerators = [];
        $this->propertyCodeGenerators = [];
        $this->methodCodeGenerators = [];
        $this->annotationGenerators = [];
    }

    /**
     * @param ContextVisitorInterface $visitor
     */
    public function changeContext(ContextVisitorInterface $visitor): void
    {
        $visitor->visit($this->classContext);
    }

    /**
     * @param ConstantCodeGeneratorInterface[] $constantCodeGenerators
     */
    public function setConstantCodeGenerators(array $constantCodeGenerators): void
    {
        $this->constantCodeGenerators = [];

        foreach ($constantCodeGenerators as $constantCodeGenerator) {
            $this->addConstantCodeGenerator($constantCodeGenerator);
        }
    }

    /**
     * @param ConstantCodeGeneratorInterface $constantCodeGenerator
     */
    public function addConstantCodeGenerator(ConstantCodeGeneratorInterface $constantCodeGenerator): void
    {
        if (in_array($constantCodeGenerator, $this->constantCodeGenerators)) {
            return;
        }

        $this->constantCodeGenerators[] = $constantCodeGenerator;
    }

    /**
     * @param ConstantCodeGeneratorInterface $constantCodeGenerator
     */
    public function removeConstantCodeGenerator(ConstantCodeGeneratorInterface $constantCodeGenerator): void
    {
        $key = array_search($constantCodeGenerator, $this->constantCodeGenerators);
        if ($key === false) {
            return;
        }

        unset($this->constantCodeGenerators[$key]);
    }

    /**
     * @return ConstantCodeGeneratorInterface[]
     */
    public function getConstantCodeGenerators(): array
    {
        return $this->constantCodeGenerators;
    }

    /**
     * @param callable $filter
     *
     * @return ConstantCodeGeneratorInterface[]
     */
    public function findConstantCodeGenerators(callable $filter): array
    {
        return array_filter(
            $this->constantCodeGenerators,
            $filter
        );
    }

    /**
     * @param PropertyCodeGenerator[] $propertyCodeGenerators
     */
    public function setPropertyCodeGenerators(array $propertyCodeGenerators)
    {
        $this->propertyCodeGenerators = [];

        foreach ($propertyCodeGenerators as $propertyCodeGenerator) {
            $this->addPropertyCodeGenerator($propertyCodeGenerator);
        }
    }

    /**
     * @param PropertyCodeGenerator $propertyCodeGenerator
     */
    public function addPropertyCodeGenerator(PropertyCodeGenerator $propertyCodeGenerator)
    {
        if (in_array($propertyCodeGenerator, $this->propertyCodeGenerators)) {
            return;
        }

        $this->propertyCodeGenerators[] = $propertyCodeGenerator;
    }

    /**
     * @param PropertyCodeGenerator $propertyCodeGenerator
     */
    public function removePropertyCodeGenerator(PropertyCodeGenerator $propertyCodeGenerator): void
    {
        $key = array_search($propertyCodeGenerator, $this->propertyCodeGenerators);
        if ($key === false) {
            return;
        }

        unset($this->propertyCodeGenerators[$key]);
    }

    /**
     * @return PropertyCodeGenerator[]
     */
    public function getPropertyCodeGenerators(): array
    {
        return $this->propertyCodeGenerators;
    }

    /**
     * @param callable $filter
     *
     * @return PropertyCodeGenerator[]
     */
    public function findPropertyCodeGenerators(callable $filter): array
    {
        return array_filter(
            $this->propertyCodeGenerators,
            $filter
        );
    }

    /**
     * @param MethodCodeGenerator[] $methodCodeGenerators
     */
    public function setMethodCodeGenerators(array $methodCodeGenerators): void
    {
        $this->methodCodeGenerators = [];

        foreach ($methodCodeGenerators as $methodCodeGenerator) {
            $this->addMethodCodeGenerator($methodCodeGenerator);
        }
    }

    /**
     * @param MethodCodeGenerator $methodCodeGenerator
     */
    public function addMethodCodeGenerator(MethodCodeGenerator $methodCodeGenerator): void
    {
        if (in_array($methodCodeGenerator, $this->methodCodeGenerators)) {
            return;
        }

        $this->methodCodeGenerators = $methodCodeGenerator;
    }

    /**
     * @param MethodCodeGenerator $methodCodeGenerator
     */
    public function removeMethodCodeGenerator(MethodCodeGenerator $methodCodeGenerator): void
    {
        $key = array_search($methodCodeGenerator, $this->methodCodeGenerators);
        if ($key === false) {
            return;
        }

        unset($this->methodCodeGenerators[$key]);
    }

    /**
     * @return MethodCodeGenerator[]
     */
    public function getMethodCodeGenerators(): array
    {
        return $this->methodCodeGenerators;
    }

    /**
     * @param callable $filter
     *
     * @return MethodCodeGenerator[]
     */
    public function findMethodCodeGenerators(callable $filter): array
    {
        return array_filter(
            $this->methodCodeGenerators,
            $filter
        );
    }

    /**
     * @param AnnotationGeneratorInterface[] $annotationGenerators
     */
    public function setAnnotationGenerators(array $annotationGenerators)
    {
        $this->annotationGenerators = [];

        foreach ($annotationGenerators as $annotationGenerator) {
            $this->addMethodCodeGenerator($annotationGenerator);
        }
    }

    /**
     * @param AnnotationGeneratorInterface $annotationGenerator
     */
    public function addAnnotationGenerator(AnnotationGeneratorInterface $annotationGenerator)
    {
        if (in_array($annotationGenerator, $this->annotationGenerators)) {
            return;
        }

        $this->annotationGenerators = $annotationGenerator;
    }

    /**
     * @param AnnotationGeneratorInterface $annotationGenerator
     */
    public function removeAnnotationGenerator(AnnotationGeneratorInterface $annotationGenerator)
    {
        $key = array_search($annotationGenerator, $this->annotationGenerators);
        if ($key === false) {
            return;
        }

        unset($this->annotationGenerators[$key]);
    }

    /**
     * @return AnnotationGeneratorInterface[]
     */
    public function getAnnotationGenerators(): array
    {
        return $this->annotationGenerators;
    }

    /**
     * @param callable $filter
     *
     * @return AnnotationGeneratorInterface[]
     */
    public function findAnnotationGenerators(callable $filter): array
    {
        return array_filter(
            $this->annotationGenerators,
            $filter
        );
    }

    /**
     * @return Node
     */
    public function getNode()
    {
        $factory = new BuilderFactory();

        $root = $factory->class(ClassName::extractClass($this->classContext->getClassName()))
            ->setDocComment($this->compileDocComment());

        if ($parent = $this->classContext->getParentClass()) {
            if ($parent->getAlias() !== null) {
                $root->extend($parent->getAlias());
            } else {
                $root->extend(ClassName::extractClass($parent->getClassName()));
            }
        }

        foreach ($this->classContext->getImplementedInterfaces() as $implementedInterface) {
            if ($implementedInterface->getAlias()) {
                $root->implement($implementedInterface->getAlias());
            } else {
                $root->implement(ClassName::extractClass($implementedInterface->getClassName()));
            }
        }

        $root->addStmt(
            new Node\Stmt\TraitUse(
                array_map(function(ClassReferenceContext $item) {
                    if ($item->getAlias()) {
                        return new Node\Name\Relative($item->getAlias());
                    } else {
                        return new Node\Name\Relative(ClassName::extractClass($item->getClassName()));
                    }
                }, $this->classContext->getUsedTraits())
            )
        );

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

    /**
     * @return string
     */
    protected function compileDocComment()
    {
        $annotationSerializer = new AnnotationSerializer();

        return '/**' . PHP_EOL
        .array_walk(
            $this->annotationGenerators,
            function(AnnotationGeneratorInterface $current) use($annotationSerializer) {
                return $annotationSerializer->serialize($current) . PHP_EOL;
            }
        )
        .' */';
    }
}
