<?php

namespace Kiboko\Component\AkeneoProductValues\Config;

use Kiboko\Component\AkeneoProductValues\CodeContext\ClassReferenceContext;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ClassCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\InterfaceCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\PropertyCodeGenerator;

trait PropertyAwareSpecBuilderTrait
{
    /**
     * @var PropertyCodeGenerator[]
     */
    private $properties;

    /**
     * @return InterfaceCodeGenerator[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param callable $filter
     *
     * @return InterfaceCodeGenerator[]
     */
    public function findProperties(callable $filter): array
    {
        return array_filter(
            $this->properties,
            $filter
        );
    }

    /**
     * @param ClassCodeGenerator $class
     * @param array $values
     */
    public function buildProperties(ClassCodeGenerator $class, array $values): void
    {
        foreach ($values as $code => $config) {
            $class->addPropertyCodeGenerator(
                $property = new PropertyCodeGenerator(
                    $class,
                    $code,
                    [],
                    $config['nullable'] ?? false,
                    $config['array'] ?? false
                )
            );

            if ($config['type'] === 'dimension') {
                throw new \RuntimeException('Not implemented yet.');
            } else if ($config['type'] === 'enum') {
                throw new \RuntimeException('Not implemented yet.');
            } else if ($config['type'] === 'asset') {
                throw new \RuntimeException('Not implemented yet.');
            } else if ($config['type'] === 'wysiwyg') {
                throw new \RuntimeException('Not implemented yet.');
            } else {
                $property->setTypeHint(
                    new ClassReferenceContext($config['type'])
                );
            }
        }
    }
}
