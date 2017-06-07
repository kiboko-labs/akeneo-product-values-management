<?php

namespace Kiboko\Component\AkeneoProductValues\CodeContext;

class ImplementInterfaceContextVisitor implements ContextVisitorInterface
{
    /**
     * @var ClassReferenceContext
     */
    private $interface;

    /**
     * ImplementInterfaceContextVisitor constructor.
     *
     * @param ClassReferenceContext $interface
     */
    public function __construct(ClassReferenceContext $interface)
    {
        $this->interface = $interface;
    }

    /**
     * @param ClassContext $context
     */
    public function visit(ClassContext $context): void
    {
        $context->addImplementedInterface($this->interface);
    }
}
