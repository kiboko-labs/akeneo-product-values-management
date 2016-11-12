<?php


namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

class DoctrineJoinColumnsAnnotationGenerator implements AnnotationGeneratorListInterface
{
    /**
     * @var AnnotationGeneratorList
     */
    private $list;

    /**
     * DoctrineEntityAnnotationGenerator constructor.
     * @param array $children
     */
    public function __construct(array $children)
    {
        $this->list = new AnnotationGeneratorList($children);
    }

    /**
     * @param AnnotationGeneratorInterface $child
     */
    public function addChild(AnnotationGeneratorInterface $child)
    {
        $this->list->addChild($child);
    }

    /**
     * @return AnnotationGeneratorInterface[]
     */
    public function getChildren()
    {
        return $this->list->getChildren();
    }

    /**
     * @param AnnotationGeneratorInterface[] $children
     */
    public function setChildren(array $children)
    {
        $this->list->setChildren($children);
    }

    /**
     * @return string
     */
    public function getAnnotation()
    {
        return '@ORM\\JoinColumns(' . $this->list->getAnnotation() . ')';
    }
}
