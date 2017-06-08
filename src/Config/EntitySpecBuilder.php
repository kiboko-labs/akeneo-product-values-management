<?php

namespace Kiboko\Component\AkeneoProductValues\Config;

use Kiboko\Component\AkeneoProductValues\CodeContext\ClassContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\ClassReferenceContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\ContextVisitorInterface;
use Kiboko\Component\AkeneoProductValues\CodeContext\ImplementInterfaceContextVisitor;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ClassCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\FileCodeGenerator;
use Kiboko\Component\AkeneoProductValues\Helper\ClassName;

class EntitySpecBuilder implements SpecificationInterface
{
    use ConstantSpecProvider;
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
     * @var EnumSpecBuilder
     */
    private $enumSpec;

    /**
     * @var ContractSpecBuilder
     */
    private $contractSpec;

    /**
     * EntitySpecBuilder constructor.
     *
     * @param \string[] $psr4Config
     * @param EnumSpecBuilder $enumSpec
     * @param ContractSpecBuilder $contractSpec
     */
    public function __construct(array $psr4Config, EnumSpecBuilder $enumSpec, ContractSpecBuilder $contractSpec)
    {
        $this->entities = [];
        $this->psr4Config = $psr4Config;
        $this->enumSpec = $enumSpec;
        $this->contractSpec = $contractSpec;
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

            $class = $this->constants[$item['name']] = new ClassCodeGenerator(
                $generator,
                new ClassContext(
                    $item['name']
                )
            );

            if (isset($item['contracts'])) {
                foreach ($item['contracts'] as $contract) {
                    $class->changeContext(new ImplementInterfaceContextVisitor(
                        new ClassReferenceContext($contract)
                    ));
                }
            }

            if (isset($item['constants'])) {
                $this->buildConstants($class, $item['constants']);
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
