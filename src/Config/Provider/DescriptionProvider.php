<?php

namespace Kiboko\Component\AkeneoProductValues\Config\Provider;

use Kiboko\Component\AkeneoProductValues\CodeContext\ContextInterface;
use Kiboko\Component\AkeneoProductValues\CodeContext\ContextVisitorInterface;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ContextAwareCodeGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\Config\Specification\SpecificationInterface;
use PhpParser\Builder;

class DescriptionProvider implements ProviderInterface
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
            $section === 'description' &&
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
            private $description;

            /**
             * @param string $description
             */
            public function __construct(string $description)
            {
                $this->description = $description;
            }

            public function visit(ContextInterface $context): void
            {
                // $context->setDocComment($this->description);
            }

        });
    }
}
