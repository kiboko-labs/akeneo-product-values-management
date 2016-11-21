<?php

namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

trait CompositeAnnotationGeneratorTrait
{
    private $children = [];

    /**
     * @return \Traversable
     */
    public function getChildren()
    {
        foreach ($this->children as $child) {
            yield $child;
        }
    }

    /**
     * @param array $children
     *
     * @return $this
     */
    public function setChildren(array $children)
    {
        $this->children = [];
        foreach ($children as $child) {
            if (!$child instanceof AnnotationGeneratorInterface) {
                continue;
            }

            $this->children[] = $child;
        }
    }

    /**
     * @param AnnotationGeneratorInterface $child
     *
     * @return $this
     */
    public function addChildren(AnnotationGeneratorInterface $child)
    {
        $this->children[] = $child;
    }

    /**
     * @return int
     */
    public function countChildren()
    {
        return count($this->children);
    }
}
