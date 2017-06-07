<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\CodeContext\ArgumentContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\ClassReferenceContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\DefaultValueContextInterface;
use Kiboko\Component\AkeneoProductValues\Helper\ClassName;
use PhpParser\Builder;
use PhpParser\BuilderFactory;
use PhpParser\Node;

interface ConstantCodeGeneratorInterface extends Builder
{
}
