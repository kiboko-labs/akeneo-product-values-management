<?php


namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

class DoctrineJoinTableAnnotationGenerator implements AnnotationGeneratorInterface
{
    use DoctrineAnnotationGeneratorTrait;

    /**
     * DoctrineJoinColumnAnnotationGenerator constructor.
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

        return '@ORM\\JoinTable('.implode(',', $options).')';
    }
}
