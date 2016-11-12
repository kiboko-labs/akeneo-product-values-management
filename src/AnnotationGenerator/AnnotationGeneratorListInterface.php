<?php

namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

interface AnnotationGeneratorListInterface extends AnnotationGeneratorInterface
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
