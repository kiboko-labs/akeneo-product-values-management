<?php

namespace Kiboko\Component\AkeneoProductValues\Config;

use Kiboko\Component\AkeneoProductValues\CodeContext\ClassContext;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\FileCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\InterfaceCodeGenerator;
use Kiboko\Component\AkeneoProductValues\Config\Provider\ProviderInterface;
use Kiboko\Component\AkeneoProductValues\Config\Specification\SpecificationInterface;
use Kiboko\Component\AkeneoProductValues\Helper\ClassName;

class EnumSpecification implements SpecificationInterface
{
    /**
     * @var InterfaceCodeGenerator[]
     */
    private $enums;

    /**
     * @var string[]
     */
    private $psr4Config;

    /**
     * @var ProviderInterface[]
     */
    private $providers;

    /**
     * EnumSpecBuilder constructor.
     *
     * @param \string[] $psr4Config
     * @param ProviderInterface[] $providers
     */
    public function __construct(
        array $psr4Config,
        array $providers
    ) {
        $this->enums = [];
        $this->psr4Config = $psr4Config;

        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }
    }

    public function addProvider(ProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }

    /**
     * @return InterfaceCodeGenerator[]
     */
    public function getEnums(): array
    {
        return $this->enums;
    }

    /**
     * @param callable $filter
     *
     * @return InterfaceCodeGenerator[]
     */
    public function filterEnums(callable $filter): array
    {
        return array_filter(
            $this->enums,
            $filter,
            ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * @param array $config
     *
     * @return FileCodeGenerator[]
     */
    public function build(array $config): array
    {
        $generators = [];

        foreach ($config as $item) {
            $generators[] = $generator = new FileCodeGenerator(
                ClassName::calculateFilePath($this->psr4Config, $item['name']),
                ClassName::extractNamespace($item['name'])
            );

            $class = $this->enums[$item['name']] = new InterfaceCodeGenerator(
                $generator,
                new ClassContext(
                    $item['name']
                )
            );

            foreach ($item as $section => $data) {
                foreach ($this->providers as $provider) {
                    if (!$provider->canProvide($this, $class, $section, $data)) {
                        continue;
                    }

                    $provider->provide($this, $class, $section, $data);
                }
            }
        }

        return $generators;
    }
}
