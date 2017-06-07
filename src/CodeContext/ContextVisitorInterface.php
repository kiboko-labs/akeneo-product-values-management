<?php

namespace Kiboko\Component\AkeneoProductValues\CodeContext;

interface ContextVisitorInterface
{
    /**
     * @param ClassContext $context
     */
    public function visit(ClassContext $context): void;
}
