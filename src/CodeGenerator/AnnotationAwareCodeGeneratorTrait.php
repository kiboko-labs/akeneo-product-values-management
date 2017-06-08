<?php

declare(strict_types=1);

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationSerializer;

trait AnnotationAwareCodeGeneratorTrait
{
    /**
     * @var AnnotationGeneratorInterface[]
     */
    private $annotationGenerators = [];

    /**
     * @param AnnotationGeneratorInterface[] $annotationGenerators
     */
    public function setAnnotationGenerators(array $annotationGenerators): void
    {
        $this->annotationGenerators = [];

        foreach ($annotationGenerators as $annotationGenerator) {
            $this->addMethodCodeGenerator($annotationGenerator);
        }
    }

    /**
     * @param AnnotationGeneratorInterface $annotationGenerator
     */
    public function addAnnotationGenerator(AnnotationGeneratorInterface $annotationGenerator): void
    {
        if (in_array($annotationGenerator, $this->annotationGenerators)) {
            return;
        }

        $this->annotationGenerators = $annotationGenerator;
    }

    /**
     * @param AnnotationGeneratorInterface $annotationGenerator
     */
    public function removeAnnotationGenerator(AnnotationGeneratorInterface $annotationGenerator): void
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
     * @return string
     */
    protected function compileDocComment()
    {
        if (count($this->annotationGenerators) <= 0) {
            return '';
        }

        $annotationSerializer = new AnnotationSerializer();

        return '/**' . PHP_EOL
            .implode('', array_map(
                function(AnnotationGeneratorInterface $current) use($annotationSerializer) {
                    return $annotationSerializer->serialize($current) . PHP_EOL;
                },
                $this->annotationGenerators
            ))
            .' */';
    }
}
