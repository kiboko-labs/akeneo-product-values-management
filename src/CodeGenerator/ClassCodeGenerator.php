<?php

declare(strict_types=1);

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationSerializer;
use Kiboko\Component\AkeneoProductValues\CodeContext\ClassContext;
use Kiboko\Component\AkeneoProductValues\Helper\ClassName;
use PhpParser\Builder;
use PhpParser\BuilderFactory;
use PhpParser\Node;

class ClassCodeGenerator implements Builder
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
        $this->parentGenerator = $parentGenerator;
        $this->classContext = $classContext;
        $this->propertyCodeGenerators = [];
        $this->methodCodeGenerators = [];
        $this->annotationGenerators = [];
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
     * @param MethodCodeGenerator[] $methodCodeGenerators
     */
    public function setMethodCodeGenerators(array $methodCodeGenerators)
    {
        $this->methodCodeGenerators = [];

        foreach ($methodCodeGenerators as $methodCodeGenerator) {
            $this->addMethodCodeGenerator($methodCodeGenerator);
        }
    }

    /**
     * @param MethodCodeGenerator $methodCodeGenerator
     */
    public function addMethodCodeGenerator(MethodCodeGenerator $methodCodeGenerator)
    {
        if (in_array($methodCodeGenerator, $this->methodCodeGenerators)) {
            return;
        }

        $this->methodCodeGenerators = $methodCodeGenerator;
    }

    /**
     * @param MethodCodeGenerator $methodCodeGenerator
     */
    public function removeMethodCodeGenerator(MethodCodeGenerator $methodCodeGenerator)
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

        foreach ($this->classContext->getUsedTraits() as $usedTrait) {
            $root->addStmt(
                $factory->use(ClassName::extractClass($usedTrait))
            );
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
