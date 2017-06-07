<?php

namespace Kiboko\Component\AkeneoProductValues\Config;

use Kiboko\Component\AkeneoProductValues\CodeGenerator\FileCodeGenerator;

interface SpecBuilderInterface
{
    /**
     * @param array $config
     *
     * @return FileCodeGenerator[]
     */
    public function build(array $config): array;
}
