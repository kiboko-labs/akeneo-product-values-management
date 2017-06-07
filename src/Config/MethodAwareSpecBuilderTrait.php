<?php

namespace Kiboko\Component\AkeneoProductValues\Config;

use Doctrine\Common\Inflector\Inflector;
use Kiboko\Component\AkeneoProductValues\CodeContext\ArgumentContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\ClassReferenceContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\DefaultValueContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\ReturnContext;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ClassCodeGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\InterfaceCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\MethodCodeGenerator;

trait MethodAwareSpecBuilderTrait
{
    /**
     * @var InterfaceCodeGenerator[]
     */
    private $methods;

    /**
     * @return InterfaceCodeGenerator[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param callable $filter
     *
     * @return InterfaceCodeGenerator[]
     */
    public function findMethods(callable $filter): array
    {
        return array_filter(
            $this->methods,
            $filter
        );
    }

    /**
     * @param ClassCodeGeneratorInterface $class
     * @param array $values
     */
    public function buildMethods(ClassCodeGeneratorInterface $class, array $values): void
    {
        // FIXME: caca prout prout
        // NOTE: almost not trying to deliberately win a C.R.A.P. metric contest
        foreach ($values as $code => $config) {
            $class->addMethodCodeGenerator(
                $method = $this->methods[] = $this->buildAccessorFor(
                    $class,
                    $code,
                    new ClassReferenceContext($config['type']),
                    $config['nullable'] ?? false,
                    $config['array'] ?? false
                )
            );

            if (isset($config['behaviors']['localized']) || isset($config['behaviors']['fallback'])) {
                $method->addArgument(
                    new ArgumentContext(
                        'locale',
                        new ClassReferenceContext(
                            'Akeneo\\Component\\Localization\\Model\\TranslationInterface'
                        ),
                        new DefaultValueContext('null'),
                        $config['behaviors']['fallback'] ?? false,
                        false
                    )
                );
            }

            if ($config['array'] ?? false) {
                $class->addMethodCodeGenerator(
                    $this->methods[] = $this->buildAdderFor(
                        $class,
                        $code,
                        new ClassReferenceContext($config['type'])
                    )
                );

                $class->addMethodCodeGenerator(
                    $this->methods[] = $this->buildRemoverFor(
                        $class,
                        $code,
                        new ClassReferenceContext($config['type'])
                    )
                );
            }

            $class->addMethodCodeGenerator(
                $method = $this->methods[] = $this->buildMutatorFor(
                    $class,
                    $code,
                    new ClassReferenceContext($config['type']),
                    $config['nullable'] ?? false,
                    $config['array'] ?? false
                )
            );

            if (isset($config['behaviors']['localized']) || isset($config['behaviors']['fallback'])) {
                $method->addArgument(
                    new ArgumentContext(
                        'locale',
                        new ClassReferenceContext(
                            'Akeneo\\Component\\Localization\\Model\\TranslationInterface'
                        ),
                        new DefaultValueContext('null'),
                        $config['behaviors']['fallback'] ?? false,
                        false
                    )
                );
            }

            if ($config['type'] === 'dimension') {
                $class->addMethodCodeGenerator(
                    $this->methods[] = $this->buildAccessorFor(
                        $class,
                        $code . 'Unit',
                        new ClassReferenceContext('string'),
                        $config['nullable'] ?? false,
                        false
                    )
                );

                $class->addMethodCodeGenerator(
                    $method = $this->methods[] = $this->buildMutatorFor(
                        $class,
                        $code. 'Unit',
                        new ClassReferenceContext('string'),
                        $config['nullable'] ?? false,
                        $config['array'] ?? false
                    )
                );
            }
        }
    }

    /**
     * @param ClassCodeGeneratorInterface $class
     * @param string $name
     * @param ClassReferenceContext $type
     * @param bool $isNullable
     * @param bool $isArray
     *
     * @return MethodCodeGenerator
     */
    public function buildAccessorFor(
        ClassCodeGeneratorInterface $class,
        string $name,
        ClassReferenceContext $type,
        bool $isNullable,
        bool $isArray
    ): MethodCodeGenerator {
        $method = new MethodCodeGenerator(
            $class,
            'get' . ucfirst(Inflector::camelize($name))
        );

        $method->setReturnType(
            new ReturnContext(
                $type,
                $isNullable,
                $isArray
            )
        );

        return $method;
    }

    /**
     * @param ClassCodeGeneratorInterface $class
     * @param string $name
     * @param ClassReferenceContext $type
     * @param bool $isNullable
     * @param bool $isArray
     *
     * @return MethodCodeGenerator
     */
    public function buildMutatorFor(
        ClassCodeGeneratorInterface $class,
        string $name,
        ClassReferenceContext $type,
        bool $isNullable,
        bool $isArray
    ): MethodCodeGenerator {
        $method = new MethodCodeGenerator(
            $class,
            'set' . ucfirst(Inflector::camelize($name))
        );

        $method->addArgument(
            new ArgumentContext($name, $type, null, $isNullable, $isArray)
        );

        $method->setReturnType(
            new ReturnContext(
                new ClassReferenceContext('void')
            )
        );

        return $method;
    }

    /**
     * @param ClassCodeGeneratorInterface $class
     * @param string $name
     * @param ClassReferenceContext $type
     *
     * @return MethodCodeGenerator
     */
    public function buildAdderFor(
        ClassCodeGeneratorInterface $class,
        string $name,
        ClassReferenceContext $type
    ): MethodCodeGenerator {
        $method = new MethodCodeGenerator(
            $class,
            'add' . ucfirst(Inflector::camelize(Inflector::singularize($name)))
        );

        $method->addArgument(
            new ArgumentContext($name, $type, null)
        );

        $method->setReturnType(
            new ReturnContext(
                new ClassReferenceContext('void')
            )
        );

        return $method;
    }

    /**
     * @param ClassCodeGeneratorInterface $class
     * @param string $name
     * @param ClassReferenceContext $type
     *
     * @return MethodCodeGenerator
     */
    public function buildRemoverFor(
        ClassCodeGeneratorInterface $class,
        string $name,
        ClassReferenceContext $type
    ): MethodCodeGenerator {
        $method = new MethodCodeGenerator(
            $class,
            'remove' . ucfirst(Inflector::camelize(Inflector::singularize($name)))
        );

        $method->addArgument(
            new ArgumentContext($name, $type, null)
        );

        $method->setReturnType(
            new ReturnContext(
                new ClassReferenceContext('void')
            )
        );

        return $method;
    }
}
