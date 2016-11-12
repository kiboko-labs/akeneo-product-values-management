<?php


namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

class DoctrineOneToOneAnnotationGenerator implements AnnotationGeneratorInterface
{
    use DoctrineAnnotationGeneratorTrait;

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

        return '@ORM\\OneToOne('.implode(',', $options).')';
    }
}
