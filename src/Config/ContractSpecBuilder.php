<?php

namespace Kiboko\Component\AkeneoProductValues\Config;

use Kiboko\Component\AkeneoProductValues\CodeContext\ClassContext;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\FileCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\InterfaceCodeGenerator;
use Kiboko\Component\AkeneoProductValues\Config\Provider\ProviderInterface;
use Kiboko\Component\AkeneoProductValues\Config\Specification\ConstantAwareSpecificationTrait;
use Kiboko\Component\AkeneoProductValues\Config\Specification\SpecificationInterface;
use Kiboko\Component\AkeneoProductValues\Helper\ClassName;

class ContractSpecBuilder implements SpecificationInterface
{
    use ConstantAwareSpecificationTrait;
    use MethodAwareSpecBuilderTrait;

    /**
     * @var InterfaceCodeGenerator[]
     */
    private $contracts;

    /**
     * @var string[]
     */
    private $psr4Config;

    /**
     * @var EnumSpecBuilder
     */
    private $enumSpec;

    /**
     * @var ProviderInterface[]
     */
    private $providers;

    /**
     * EnumSpecBuilder constructor.
     *
     * @param \string[] $psr4Config
     */
    public function __construct(
        array $psr4Config,
        EnumSpecBuilder $enumSpec,
        array $providers
    ) {
        $this->contracts = [];
        $this->psr4Config = $psr4Config;
        $this->enumSpec = $enumSpec;

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
    public function getContracts(): array
    {
        return $this->contracts;
    }

    /**
     * @param callable $filter
     *
     * @return InterfaceCodeGenerator[]
     */
    public function filterContracts(callable $filter): array
    {
        return array_filter(
            $this->contracts,
            $filter
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

            $class = $this->contracts[$item['name']] = new InterfaceCodeGenerator(
                $generator,
                new ClassContext(
                    $item['name']
                )
            );

            foreach ($item as $section => $data) {
                foreach ($this->providers as $provider) {
                    if (!$provider->canProvide($class, $section, $data)) {
                        continue;
                    }

                    $provider->provide($class, $section, $data);
                }
            }
            continue;
            if (isset($item['constants'])) {
                $this->buildConstants($class, $item['constants']);
            }

            if (isset($item['fields'])) {
                $this->buildMethods($class, $item['fields']);
            }
        }

        return $generators;
    }
}