<?php

namespace Kiboko\Component\AkeneoProductValues\CodeContext;

interface ContextVisitorInterface
{
    /**
     * @param ContextInterface $context
     */
    public function visit(ContextInterface $context): void;
}
