<?php

namespace Kiboko\Component\AkeneoProductValues\Config;

use Kiboko\Component\AkeneoProductValues\CodeContext\ClassContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\ClassReferenceContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\ImplementInterfaceContextVisitor;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ClassCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\FileCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\InterfaceCodeGenerator;
use Kiboko\Component\AkeneoProductValues\Config\Provider\ProviderInterface;
use Kiboko\Component\AkeneoProductValues\Config\Specification\ConstantAwareSpecificationTrait;
use Kiboko\Component\AkeneoProductValues\Config\Specification\SpecificationInterface;
use Kiboko\Component\AkeneoProductValues\Helper\ClassName;

class EntitySpecification implements SpecificationInterface
{
    use ConstantAwareSpecificationTrait;
    use PropertyAwareSpecBuilderTrait;
    use MethodAwareSpecBuilderTrait;

    /**
     * @var ClassCodeGenerator[]
     */
    private $entities;

    /**
     * @var string[]
     */
    private $psr4Config;

    /**
     * @var EnumSpecification
     */
    private $enumSpec;

    /**
     * @var ContractSpecification
     */
    private $contractSpec;

    /**
     * @var ProviderInterface[]
     */
    private $providers;

    /**
     * EntitySpecBuilder constructor.
     *
     * @param \string[] $psr4Config
     * @param EnumSpecification $enumSpec
     * @param ContractSpecification $contractSpec
     * @param ProviderInterface[] $providers
     */
    public function __construct(
        array $psr4Config,
        EnumSpecification $enumSpec,
        ContractSpecification $contractSpec,
        array $providers
    ) {
        $this->entities = [];
        $this->psr4Config = $psr4Config;
        $this->enumSpec = $enumSpec;
        $this->contractSpec = $contractSpec;

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
        return $this->contractSpec->getContracts();
    }

    /**
     * @param callable $filter
     *
     * @return InterfaceCodeGenerator[]
     */
    public function filterContracts(callable $filter): array
    {
        return $this->contractSpec->filterContracts($filter);
    }

    /**
     * @return ClassCodeGenerator[]
     */
    public function getEntities(): array
    {
        return $this->entities;
    }

    /**
     * @param callable $filter
     *
     * @return ClassCodeGenerator[]
     */
    public function filterEntities(callable $filter): array
    {
        return array_filter(
            $this->entities,
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

            $class = $this->entities[$item['name']] = new ClassCodeGenerator(
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
            continue;

            if (isset($item['contracts'])) {
                foreach ($item['contracts'] as $contract) {
                    $class->changeContext(new ImplementInterfaceContextVisitor(
                        new ClassReferenceContext($contract)
                    ));
                }
            }

            if (isset($item['contracts'])) {
                foreach ($item['contracts'] as $contract) {
                    //$this->contractSpec->getMethods();
                }
            }

            if (isset($item['fields'])) {
                $this->buildProperties($class, $item['fields']);

                $this->buildMethods($class, $item['fields']);
            }
        }

        return $generators;
    }
}
