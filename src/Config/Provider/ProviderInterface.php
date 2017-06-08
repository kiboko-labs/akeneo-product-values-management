<?php

namespace Kiboko\Component\AkeneoProductValues\Config\Provider;

use Kiboko\Component\AkeneoProductValues\Config\Specification\SpecificationInterface;
use PhpParser\Builder;

interface ProviderInterface
{
    /**
     * @param SpecificationInterface $specification
     * @param Builder $builder
     * @param string $section
     * @param mixed $data
     *
     * @return bool
     */
    public function canProvide(SpecificationInterface $specification, Builder $builder, string $section, $data): bool;

    /**
     * @param SpecificationInterface $specification
     * @param Builder $builder
     * @param string $section
     * @param mixed $data
     */
    public function provide(SpecificationInterface $specification, Builder $builder, string $section, $data): void;
}
