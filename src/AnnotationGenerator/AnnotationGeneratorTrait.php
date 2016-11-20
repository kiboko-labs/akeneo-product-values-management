<?php

namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

trait AnnotationGeneratorTrait
{
    /**
     * @var string
     */
    private $annotationClass;

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
}
