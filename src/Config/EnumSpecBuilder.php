<?php

namespace Kiboko\Component\AkeneoProductValues\Config;

use Kiboko\Component\AkeneoProductValues\CodeContext\ClassContext;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\FileCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\InterfaceCodeGenerator;
use Kiboko\Component\AkeneoProductValues\Helper\ClassName;

class EnumSpecBuilder implements SpecBuilderInterface
{
    use ConstantAwareSpecBuilderTrait;

    /**
     * @var string[]
     */
    private $psr4Config;

    /**
     * EnumSpecBuilder constructor.
     *
     * @param \string[] $psr4Config
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
        $generators = [];

        foreach ($config as $item) {
            $generators[] = $generator = new FileCodeGenerator(
                ClassName::calculateFilePath($this->psr4Config, $item['name']),
                ClassName::extractNamespace($item['name'])
            );

            $class = $this->constants[$item['name']] = new InterfaceCodeGenerator(
                $generator,
                new ClassContext(
                    $item['name']
                )
            );

            if (isset($item['values'])) {
                $this->buildConstants($class, $item['values']);
            }
        }

        return $generators;
    }
}
