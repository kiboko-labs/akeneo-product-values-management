<?php


namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

class AnnotationGeneratorList implements AnnotationGeneratorListInterface
{
    /**
     * @var AnnotationGeneratorInterface[]
     */
    private $children;

    /**
     * AnnotationGeneratorList constructor.
     * @param AnnotationGeneratorInterface[] $children
     */
    public function __construct(array $children)
    {
        $this->children = [];
        $this->setChildren($children);
    }

    /**
     * @return string
     */
    public function getAnnotation()
    {
        $serializer = new AnnotationParamsSerializer();

        $serializedChildren = [];
        foreach ($serializer->serialize($this->children) as $child) {
            $serializedChildren[] = $child;
        }

        return '{ ' . implode(', ', $serializedChildren) . ' }';
    }

    /**
     * @param AnnotationGeneratorInterface $child
     */
    public function addChild(AnnotationGeneratorInterface $child)
    {
        $this->children[] = $child;
    }

    /**
     * @return AnnotationGeneratorInterface[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param AnnotationGeneratorInterface[] $children
     */
    public function setChildren(array $children)
    {
        foreach ($children as $child) {
            if (!$child instanceof AnnotationGeneratorInterface) {
                continue;
            }

            $this->children[] = $child;
        }
    }
}
