<?php

namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator\Doctrine;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorTrait;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\CompositeAnnotationGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\CompositeAnnotationGeneratorTrait;

class DoctrineJoinColumnsAnnotationGenerator implements CompositeAnnotationGeneratorInterface
{
    use AnnotationGeneratorTrait;
    use CompositeAnnotationGeneratorTrait;

    /**
     * DoctrineJoinColumnsAnnotationGenerator constructor.
     *
     * @param array $children
     */
    public function __construct(array $children)
    {
        $this->setAnnotationClass('ORM\\JoinColumns');
    }
}
