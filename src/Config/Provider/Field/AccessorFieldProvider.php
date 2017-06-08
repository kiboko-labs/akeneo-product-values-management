<?php

namespace Kiboko\Component\AkeneoProductValues\Config\Provider\Field;

use Doctrine\Common\Inflector\Inflector;
use Kiboko\Component\AkeneoProductValues\CodeContext\ClassReferenceContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\ReturnContext;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\MethodAwareCodeGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\MethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\Config\Provider\ProviderInterface;
use PhpParser\Builder;

class AccessorFieldProvider implements ProviderInterface
{
    /**
     * @param Builder $builder
     * @param string $section
     * @param mixed $data
     *
     * @return bool
     */
    public function canProvide(Builder $builder, string $section, $data): bool
    {
        return $builder instanceof MethodAwareCodeGeneratorInterface &&
            $section === 'fields' &&
            is_array($data) &&
            (!isset($data['array']) || $data['array'] !== false);
    }

    /**
     * @param Builder|MethodAwareCodeGeneratorInterface $builder
     * @param string $section
     * @param mixed $data
     */
    public function provide(Builder $builder, string $section, $data): void
    {
        foreach ($data as $field => $config) {
            $method = new MethodCodeGenerator(
                $builder,
                'get' . ucfirst(Inflector::camelize($field))
            );

            $method->setReturnType(
                new ReturnContext(
                    new ClassReferenceContext($config['type'] ?? 'string'),
                    $config['nullable'] ?? false,
                    false
                )
            );

            $builder->addMethodCodeGenerator($method);
        }
    }
}
