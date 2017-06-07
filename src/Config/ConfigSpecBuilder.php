<?php

namespace Kiboko\Component\AkeneoProductValues\Config;

use Kiboko\Component\AkeneoProductValues\CodeGenerator\FileCodeGenerator;

class ConfigSpecBuilder
{
    /**
     * @var array
     */
    private $psr4Config;

    /**
     * ConfigSpecBuilder constructor.
     * @param array $psr4Config
     */
    public function __construct(array $psr4Config)
    {
        $this->psr4Config = $psr4Config;
    }

    /**
     * @param array $config
     *
     * @return FileCodeGenerator[]
     */
    public function build(array $config): array
    {
        $files = [];

        $enumSpec = new EnumSpecBuilder(
            $this->psr4Config
        );

        if (isset($config['enums'])) {
            $files = array_merge(
                $files,
                $enumSpec->build($config['enums'])
            );
        }

        $contractSpec = new ContractSpecBuilder(
            $this->psr4Config,
            $enumSpec
        );

        if (isset($config['contracts'])) {
            $files = array_merge(
                $files,
                $contractSpec->build($config['contracts'])
            );
        }

        $entitySpec = new EntitySpecBuilder(
            $this->psr4Config,
            $enumSpec,
            $contractSpec
        );

        if (isset($config['entities'])) {
            $files = array_merge(
                $files,
                $entitySpec->build($config['entities'])
            );
        }

        return $files;
    }
}
