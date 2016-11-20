<?php

namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

interface AnnotationGeneratorInterface
{
    /**
     * @return string
     */
    public function getAnnotationClass();

    /**
     * @param string $annotationClass
     */
    public function setAnnotationClass($annotationClass);
}
