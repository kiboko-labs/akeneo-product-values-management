<?php

namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator\Doctrine;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorTrait;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\ParameterAwareTrait;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\ParameteredAnnotationGeneratorInterface;

class DoctrineManyToOneAnnotationGenerator implements ParameteredAnnotationGeneratorInterface
{
    use AnnotationGeneratorTrait;
    use ParameterAwareTrait;

    /**
     * DoctrineManyToOneAnnotationGenerator constructor.
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->setAnnotationClass('ORM\\ManyToOne');
        $this->setParams($params);
    }

    /**
     * @return string
     */
    public function getTargetEntity()
    {
        return $this->getParam('targetEntity');
    }

    /**
     * @param string $targetEntity
     */
    public function setTargetEntity($targetEntity)
    {
        $this->addParam('targetEntity', $targetEntity);
    }

    /**
     * @return array
     */
    public function getCascade()
    {
        return $this->getParam('cascade');
    }

    /**
     * @param array $cascade
     */
    public function setCascade(array $cascade)
    {
        $this->addParam('cascade', $cascade);
    }

    /**
     * @return string
     */
    public function getFetch()
    {
        return $this->getParam('fetch');
    }

    /**
     * @param string $fetch
     */
    public function setFetch($fetch)
    {
        $fetch = strtoupper($fetch);
        if (!in_array($fetch, ['LAZY', 'EAGER'])) {
            return;
        }

        $this->addParam('fetch', $fetch);
    }

    /**
     * @return string
     */
    public function getInversedBy()
    {
        return $this->getParam('inversedBy');
    }

    /**
     * @param string $inversedBy
     */
    public function setInversedBy($inversedBy)
    {
        $this->addParam('inversedBy', $inversedBy);
    }
}
