<?php

declare(strict_types=1);

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

interface InterfaceCodeGeneratorInterface extends
    ConstantAwareCodeGeneratorInterface,
    MethodAwareCodeGeneratorInterface,
    AnnotationAwareCodeGeneratorInterface,
    ContextAwareCodeGeneratorInterface
{
}
