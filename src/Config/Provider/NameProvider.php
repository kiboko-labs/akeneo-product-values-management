<?php

namespace Kiboko\Component\AkeneoProductValues\Config\Provider;

use Kiboko\Component\AkeneoProductValues\CodeContext\ContextInterface;
use Kiboko\Component\AkeneoProductValues\CodeContext\ContextVisitorInterface;
use Kiboko\Component\AkeneoProductValues\CodeContext\NamedContextInterface;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ContextAwareCodeGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\Config\Specification\SpecificationInterface;
use PhpParser\Builder;

class NameProvider implements ProviderInterface
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
        return $builder instanceof ContextAwareCodeGeneratorInterface &&
            $section === 'name' &&
            is_string($data);
    }

    /**
     * @param SpecificationInterface $specification
     * @param Builder|ContextAwareCodeGeneratorInterface $builder
     * @param string $section
     * @param mixed $data
     */
    public function provide(SpecificationInterface $specification, Builder $builder, string $section, $data): void
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
