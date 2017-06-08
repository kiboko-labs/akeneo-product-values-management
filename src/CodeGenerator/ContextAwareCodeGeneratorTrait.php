<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use Kiboko\Component\AkeneoProductValues\CodeContext\ContextVisitorInterface;

trait ContextAwareCodeGeneratorTrait
{
    /**
     * @var ClassContext
     */
    private $context;

    /**
     * @param ContextVisitorInterface $visitor
     */
    public function changeContext(ContextVisitorInterface $visitor): void
    {
        $visitor->visit($this->context);
    }
}
