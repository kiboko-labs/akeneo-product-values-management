<?php

namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

trait DoctrineAnnotationGeneratorTrait
{
    /**
     * @var array
     */
    private $params;

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param string $doctrineTypeOption
     *
     * @return mixed
     */
    public function getParam($doctrineTypeOption)
    {
        return $this->params[$doctrineTypeOption];
    }

    /**
     * @param string $doctrineTypeOption
     * @param mixed $value
     */
    public function addParam($doctrineTypeOption, $value)
    {
        $this->params[$doctrineTypeOption] = $value;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

}
