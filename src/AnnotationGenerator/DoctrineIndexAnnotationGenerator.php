<?php


namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

class DoctrineIndexAnnotationGenerator implements AnnotationGeneratorInterface
{
    use DoctrineAnnotationGeneratorTrait;

    /**
     * DoctrineIndexAnnotationGenerator constructor.
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
     * @return array
     */
    public function getColumns()
    {
        return $this->getParam('columns');
    }

    /**
     * @param string[] $columns
     */
    public function setColumns(array $columns)
    {
        $this->addParam('columns', $columns);
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->getParam('options');
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->addParam('options', $options);
    }

    /**
     * @param string $option
     * @param mixed $value
     */
    public function addOption($option, $value)
    {
        if (!in_array($option, ['where'])) {
            return;
        }

        $options = $this->getOptions();
        $options[$option] = $value;
        $this->setOptions($options);
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

        return '@ORM\\Index('.implode(',', $options).')';
    }
}
