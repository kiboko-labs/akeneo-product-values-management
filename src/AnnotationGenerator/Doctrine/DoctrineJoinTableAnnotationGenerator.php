<?php

namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator\Doctrine;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorTrait;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\ParameterAwareTrait;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\ParameteredAnnotationGeneratorInterface;

class DoctrineJoinTableAnnotationGenerator implements ParameteredAnnotationGeneratorInterface
{
    use AnnotationGeneratorTrait;
    use ParameterAwareTrait;

    /**
     * DoctrineJoinTableAnnotationGenerator constructor.
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->setAnnotationClass('ORM\\JoinTable');
        $this->setParams($params);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getParam('name');
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->addParam('name', $name);
    }

    /**
     * @return string
     */
    public function getJoinColumns()
    {
        return $this->getParam('joinColumns');
    }

    /**
     * @param string $joinColumns
     */
    public function setJoinColumns($joinColumns)
    {
        $this->addParam('joinColumns', $joinColumns);
    }

    /**
     * @return string
     */
    public function getInverseJoinColumns()
    {
        return $this->getParam('inverseJoinColumns');
    }

    /**
     * @param string $inverseJoinColumns
     */
    public function setInverseJoinColumns($inverseJoinColumns)
    {
        $this->addParam('inverseJoinColumns', $inverseJoinColumns);
    }
}
