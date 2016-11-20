<?php

namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator\Doctrine;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorTrait;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\ParameterAwareTrait;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\ParameteredAnnotationGeneratorInterface;

class DoctrineOneToManyAnnotationGenerator implements ParameteredAnnotationGeneratorInterface
{
    use AnnotationGeneratorTrait;
    use ParameterAwareTrait;

    /**
     * DoctrineOneToManyAnnotationGenerator constructor.
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->setAnnotationClass('ORM\\OneToMany');
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
     * @return bool
     */
    public function getOrphanRemoval()
    {
        return $this->getParam('orphanRemoval');
    }

    /**
     * @param bool $orphanRemoval
     */
    public function setOrphanRemoval($orphanRemoval)
    {
        $this->addParam('orphanRemoval', $orphanRemoval);
    }

    /**
     * @return string
     */
    public function getMappedBy()
    {
        return $this->getParam('mappedBy');
    }

    /**
     * @param string $mappedBy
     */
    public function setMappedBy($mappedBy)
    {
        $this->addParam('mappedBy', $mappedBy);
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
        if (!in_array($fetch, ['LAZY', 'EAGER', 'EXTRA_LAZY'])) {
            return;
        }

        $this->addParam('fetch', $fetch);
    }

    /**
     * @return string
     */
    public function getIndexBy()
    {
        return $this->getParam('indexBy');
    }

    /**
     * @param string $indexBy
     */
    public function setIndexBy($indexBy)
    {
        $this->addParam('indexBy', $indexBy);
    }
}
