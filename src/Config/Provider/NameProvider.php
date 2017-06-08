<?php

namespace Kiboko\Component\AkeneoProductValues\Config\Provider;

use Kiboko\Component\AkeneoProductValues\CodeContext\ContextInterface;
use Kiboko\Component\AkeneoProductValues\CodeContext\ContextVisitorInterface;
use Kiboko\Component\AkeneoProductValues\CodeContext\NamedContextInterface;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ContextAwareCodeGeneratorInterface;
use PhpParser\Builder;

class NameProvider implements ProviderInterface
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
        return $builder instanceof ContextAwareCodeGeneratorInterface &&
            $section === 'name' &&
            is_string($data);
    }

    /**
     * @param Builder|ContextAwareCodeGeneratorInterface $builder
     * @param string $section
     * @param mixed $data
     */
    public function provide(Builder $builder, string $section, $data): void
    {
        $builder->changeContext(new class($data) implements ContextVisitorInterface
        {
            /**
             * @var string
             */
            private $name;

            /**
             * @param string $name
             */
            public function __construct(string $name)
            {
                $this->name = $name;
            }

            public function visit(ContextInterface $context): void
            {
                if (!$context instanceof NamedContextInterface) {
                    return;
                }

                $context->setName($this->name);
            }

        });
    }
}
