<?php

namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

interface CompositeAnnotationGeneratorInterface extends AnnotationGeneratorInterface
{
    /**
     * @param AnnotationGeneratorInterface
     */
    public function addChild(AnnotationGeneratorInterface $child);

    /**
     * @return AnnotationGeneratorInterface[]
     */
    public function getChildren();

    /**
     * @param AnnotationGeneratorInterface[] $children
     */
    public function setChildren(array $children);
}
