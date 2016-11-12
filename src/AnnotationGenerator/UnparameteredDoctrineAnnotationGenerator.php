<?php


namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

class UnparameteredDoctrineAnnotationGenerator implements AnnotationGeneratorInterface
{
    private $annotationClass;

    /**
     * DoctrineEntityAnnotationGenerator constructor.
     * @param $annotationClass
     */
    public function __construct($annotationClass)
    {
        $this->annotationClass = $annotationClass;
    }

    /**
     * @return string
     */
    public function getAnnotationClass()
    {
        return $this->annotationClass;
    }

    /**
     * @param string $annotationClass
     */
    public function setAnnotationClass($annotationClass)
    {
        $this->annotationClass = $annotationClass;
    }

    /**
     * @return string
     */
    public function getAnnotation()
    {
        return '@ORM\\' . $this->annotationClass;
    }
}
