<?php


namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

class DoctrineManyToManyAnnotationGenerator implements AnnotationGeneratorInterface
{
    use DoctrineAnnotationGeneratorTrait;

    /**
     * DoctrineManyToManyAnnotationGenerator constructor.
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
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

    /**
     * @return string
     */
    public function getAnnotation()
    {
        $serializer = new AnnotationParamsSerializer();
        $options = [];
        foreach ($serializer->serialize($this->params) as $value) {
            $options[] = $value;
        }

        return '@ORM\\ManyToMany('.implode(',', $options).')';
    }
}
