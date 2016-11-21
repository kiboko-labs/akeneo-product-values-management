<?php

namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

interface CompositeAnnotationGeneratorInterface extends AnnotationGeneratorInterface
{
    /**
     * @return AnnotationGeneratorInterface[]|\Traversable
     */
    public function getChildren();

    /**
     * @param AnnotationGeneratorInterface
     */
    public function addChild(AnnotationGeneratorInterface $child);

    /**
     * @param AnnotationGeneratorInterface[] $children
     */
    public function setChildren(array $children);

    /**
     * @return int
     */
    public function countChildren();
}
