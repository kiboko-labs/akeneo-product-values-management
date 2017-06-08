<?php

namespace Kiboko\Component\AkeneoProductValues\Config\Provider;

use PhpParser\Builder;

interface ProviderInterface
{
    /**
     * @param Builder $builder
     * @param string $section
     * @param mixed $data
     *
     * @return bool
     */
    public function canProvide(Builder $builder, string $section, $data): bool;

    /**
     * @param Builder $builder
     * @param string $section
     * @param mixed $data
     */
    public function provide(Builder $builder, string $section, $data): void;
}
