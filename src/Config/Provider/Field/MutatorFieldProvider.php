<?php

namespace Kiboko\Component\AkeneoProductValues\Config\Provider\Field;

use Doctrine\Common\Inflector\Inflector;
use Kiboko\Component\AkeneoProductValues\CodeContext\ArgumentContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\ClassReferenceContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\ReturnContext;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\MethodAwareCodeGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\MethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\Config\Provider\ProviderInterface;
use Kiboko\Component\AkeneoProductValues\Config\Specification\SpecificationInterface;
use PhpParser\Builder;

class MutatorFieldProvider implements ProviderInterface
{
    /**
     * @param SpecificationInterface $specification
     * @param Builder $builder
     * @param string $section
     * @param mixed $data
     *
     * @return bool
     */
    public function canProvide(SpecificationInterface $specification, Builder $builder, string $section, $data): bool
    {
        return $builder instanceof MethodAwareCodeGeneratorInterface &&
            $section === 'fields' &&
            is_array($data);
    }

    /**
     * @param SpecificationInterface $specification
     * @param Builder|MethodAwareCodeGeneratorInterface $builder
     * @param string $section
     * @param mixed $data
     */
    public function provide(SpecificationInterface $specification, Builder $builder, string $section, $data): void
    {
        foreach ($data as $field => $config) {
            $method = new MethodCodeGenerator(
                $builder,
                'set' . ucfirst(Inflector::camelize($field))
            );

            $method->addArgument(
                new ArgumentContext(
                    $field,
                    new ClassReferenceContext($config['type'] ?? 'string'),
                    null,
                    $config['nullable'] ?? false,
                    isset($config['array']) && $config['array'] !== true
                )
            );

            $method->setReturnType(
                new ReturnContext(
                    new ClassReferenceContext('void')
                )
            );

            $builder->addMethodCodeGenerator($method);
        }
    }
}
