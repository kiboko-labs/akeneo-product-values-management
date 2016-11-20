<?php

namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator\Doctrine;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorTrait;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\ParameterAwareTrait;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\ParameteredAnnotationGeneratorInterface;

class DoctrineMappedSuperClassAnnotationGenerator implements ParameteredAnnotationGeneratorInterface
{
    use AnnotationGeneratorTrait;
    use ParameterAwareTrait;

    /**
     * DoctrineMappedSuperClassAnnotationGenerator constructor.
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->setAnnotationClass('ORM\\MappedSuperClass');
        $this->setParams($params);
    }

    /**
     * @return string
     */
    public function getRepositoryClass()
    {
        return $this->getParam('repositoryClass');
    }

    /**
     * @param string $repositoryClass
     */
    public function setRepositoryClass($repositoryClass)
    {
        $this->addParam('repositoryClass', $repositoryClass);
    }
}
