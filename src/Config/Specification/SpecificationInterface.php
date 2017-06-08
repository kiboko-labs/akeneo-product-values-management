<?php

namespace Kiboko\Component\AkeneoProductValues\Config\Specification;

use Kiboko\Component\AkeneoProductValues\CodeGenerator\FileCodeGenerator;
use Kiboko\Component\AkeneoProductValues\Config\Provider\ProviderInterface;

interface SpecificationInterface
{
    /**
     * @param array $config
     *
     * @return FileCodeGenerator[]
     */
    public function build(array $config): array;

    /**
     * @param ProviderInterface $provider
     */
    public function addProvider(ProviderInterface $provider): void;
}
