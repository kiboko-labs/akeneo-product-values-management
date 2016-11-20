<?php

namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

interface ParameteredAnnotationGeneratorInterface
    extends AnnotationGeneratorInterface
{
    /**
     * @return \Traversable
     */
    public function getParams();

    /**
     * @param string $param
     *
     * @return mixed
     */
    public function getParam($param);

    /**
     * @param string $param
     * @param mixed $value
     */
    public function addParam($param, $value);

    /**
     * @param array $params
     */
    public function setParams(array $params);

    /**
     * @return int
     */
    public function countParams();
}
