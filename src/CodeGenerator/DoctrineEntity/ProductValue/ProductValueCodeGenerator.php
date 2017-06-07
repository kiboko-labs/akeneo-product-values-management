<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\ProductValue;

use Kiboko\Component\AkeneoProductValues\CodeContext\ClassContext;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ClassCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\FileCodeGenerator;

class ProductValueCodeGenerator extends ClassCodeGenerator
{
    /**
     * @param FileCodeGenerator $parentGenerator
     * @param ClassContext $classContext
     * @param string $className
     */
    public function __construct(
        FileCodeGenerator $parentGenerator,
        ClassContext $classContext,
        string $className
    ) {
        parent::__construct($parentGenerator, $classContext);

        $parentGenerator->addUsedReference('Doctrine\\ORM\\Mapping', 'ORM');
        $parentGenerator->addUsedReference('Pim\\Component\\Catalog\\Model\\ProductValue', 'PimProductValue');
    }
}
