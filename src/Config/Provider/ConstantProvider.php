<?php

namespace Kiboko\Component\AkeneoProductValues\Config\Provider;

use Kiboko\Component\AkeneoProductValues\CodeGenerator\ConstantAwareCodeGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\StringConstantCodeGenerator;
use PhpParser\Builder;

class ConstantProvider implements ProviderInterface
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
        return $builder instanceof ConstantAwareCodeGeneratorInterface &&
            $section === 'consts' &&
            is_array($data);
    }

    /**
     * @param Builder|ConstantAwareCodeGeneratorInterface $builder
     * @param string $section
     * @param mixed $data
     */
    public function provide(Builder $builder, string $section, $data): void
    {
        foreach ($data as $constant => $value) {
            $builder->addConstantCodeGenerator(
                new StringConstantCodeGenerator($builder, $constant, $value)
            );
        }
    }
}
