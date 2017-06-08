<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use Kiboko\Component\AkeneoProductValues\CodeContext\ContextVisitorInterface;

interface ContextAwareCodeGeneratorInterface
{
    /**
     * @param ContextVisitorInterface $visitor
     */
    public function changeContext(ContextVisitorInterface $visitor): void;
}
