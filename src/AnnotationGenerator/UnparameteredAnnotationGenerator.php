<?php


namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

class UnparameteredAnnotationGenerator implements AnnotationGeneratorInterface
{
    use AnnotationGeneratorTrait;

    /**
     * DoctrineEntityAnnotationGenerator constructor.
     * @param $annotationClass
     */
    public function __construct($annotationClass)
    {
        $this->setAnnotationClass($annotationClass);
    }
}
