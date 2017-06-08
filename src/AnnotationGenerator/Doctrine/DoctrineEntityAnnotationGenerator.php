<?php

namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator\Doctrine;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorTrait;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\ParameterAwareTrait;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\ParameteredAnnotationGeneratorInterface;

class DoctrineEntityAnnotationGenerator implements ParameteredAnnotationGeneratorInterface
{
    use AnnotationGeneratorTrait;
    use ParameterAwareTrait;

    /**
     * DoctrineEntityAnnotationGenerator constructor.
     *
     * @param string $name
     * @param string $namespace
     * @param array $params
     */
    public function __construct($name, $namespace, array $params = [])
    {
        $this->setAnnotationClass('ORM\\Entity');
        $this->setParams($params + ['type' => $namespace . '\\' . $name]);
    }

    /**
     * @return string
     */
    public function getEntityClass()
    {
        return $this->getParam('entityClass');
    }

    /**
     * @param string $entityClass
     */
    public function setEntityClass($entityClass)
    {
        $this->addParam('entityClass', $entityClass);
    }
}
