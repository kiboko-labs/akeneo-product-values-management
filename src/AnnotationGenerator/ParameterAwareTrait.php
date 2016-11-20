<?php

namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

trait ParameterAwareTrait
{
    /**
     * @var array
     */
    private $params;

    /**
     * @return \Traversable
     */
    public function getParams()
    {
        foreach ($this->params as $field => $param) {
            yield $field => $param;
        }
    }

    /**
     * @param string $doctrineTypeOption
     *
     * @return mixed
     */
    public function getParam($param)
    {
        return $this->params[$param];
    }

    /**
     * @param string $param
     * @param mixed $value
     */
    public function addParam($param, $value)
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

    /**
     * @return int
     */
    public function countParams()
    {
        return count($this->params);
    }
}
