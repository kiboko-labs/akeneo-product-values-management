<?php

declare(strict_types=1);

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

interface ClassCodeGeneratorInterface extends
    ConstantAwareCodeGeneratorInterface,
    MethodAwareCodeGeneratorInterface,
    PropertyAwareCodeGeneratorInterface,
    AnnotationAwareCodeGeneratorInterface,
    ContextAwareCodeGeneratorInterface
{
}