<?php

namespace Kiboko\Component\AkeneoProductValues\Config\Provider;

use Kiboko\Component\AkeneoProductValues\CodeContext\ClassContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\ClassReferenceContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\ContextInterface;
use Kiboko\Component\AkeneoProductValues\CodeContext\ContextVisitorInterface;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ClassCodeGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\InterfaceCodeGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\Config\EntitySpecification;
use Kiboko\Component\AkeneoProductValues\Config\Specification\SpecificationInterface;
use PhpParser\Builder;

class ContractProvider implements ProviderInterface
{
    /**
     * @param SpecificationInterface $specification
     * @param Builder $builder
     * @param string $section
     * @param mixed $data
     *
     * @return bool
     */
    public function canProvide(SpecificationInterface $specification, Builder $builder, string $section, $data): bool
    {
        return $builder instanceof ClassCodeGeneratorInterface &&
            $section === 'contracts' &&
            is_array($data);
    }

    /**
     * @param SpecificationInterface $specification
     * @param Builder|ClassCodeGeneratorInterface $builder
     * @param string $section
     * @param mixed $data
     */
    public function provide(SpecificationInterface $specification, Builder $builder, string $section, $data): void
    {
        foreach ($data as $contract) {
            $builder->changeContext(new class($contract) implements ContextVisitorInterface
            {
                /**
                 * @var string
                 */
                private $interface;

                /**
                 * @param string $interface
                 */
                public function __construct(string $interface)
                {
                    $this->interface = $interface;
                }

                public function visit(ContextInterface $context): void
                {
                    if (!$context instanceof ClassContext) {
                        return;
                    }

                    $context->addImplementedInterface(
                        new ClassReferenceContext($this->interface)
                    );
                }
            });

            if (!$specification instanceof EntitySpecification) {
                return;
            }

        }

        $contracts = $specification->filterContracts(function($generator, $name) use($data) {
            return in_array($name, $data);
        });

        foreach ($contracts as $contract) {
            foreach ($contract->getMethodCodeGenerators() as $generator) {
                $builder->addMethodCodeGenerator($generator);
            }
        }
    }
}
