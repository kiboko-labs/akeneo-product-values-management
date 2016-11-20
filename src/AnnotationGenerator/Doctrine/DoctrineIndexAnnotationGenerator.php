<?php

namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator\Doctrine;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorTrait;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\ParameterAwareTrait;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\ParameteredAnnotationGeneratorInterface;

class DoctrineIndexAnnotationGenerator implements ParameteredAnnotationGeneratorInterface
{
    use AnnotationGeneratorTrait;
    use ParameterAwareTrait;

    /**
     * DoctrineIndexAnnotationGenerator constructor.
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->setAnnotationClass('ORM\\Index');
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
}
