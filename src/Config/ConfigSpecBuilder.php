<?php

namespace Kiboko\Component\AkeneoProductValues\Config;

use Kiboko\Component\AkeneoProductValues\CodeGenerator\FileCodeGenerator;
use Kiboko\Component\AkeneoProductValues\Config\Provider\ConstantProvider;
use Kiboko\Component\AkeneoProductValues\Config\Provider\DescriptionProvider;
use Kiboko\Component\AkeneoProductValues\Config\Provider\Field\AccessorFieldProvider;
use Kiboko\Component\AkeneoProductValues\Config\Provider\NameProvider;

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
            $this->psr4Config,
            [
                new NameProvider(),
                new DescriptionProvider(),
                new ConstantProvider(),
            ]
        );

        if (isset($config['enums'])) {
            $files = array_merge(
                $files,
                $enumSpec->build($config['enums'])
            );
        }

        $contractSpec = new ContractSpecBuilder(
            $this->psr4Config,
            $enumSpec,
            [
                new NameProvider(),
                new DescriptionProvider(),
                new ConstantProvider(),
                new AccessorFieldProvider(),
            ]
        );

        if (isset($config['contracts'])) {
            $files = array_merge(
                $files,
                $contractSpec->build($config['contracts'])
            );
        }
        return $files;

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
