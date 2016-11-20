<?php

namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator\Doctrine;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorTrait;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\ParameterAwareTrait;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\ParameteredAnnotationGeneratorInterface;

class DoctrineGeneratedValueAnnotationGenerator implements ParameteredAnnotationGeneratorInterface
{
    use AnnotationGeneratorTrait;
    use ParameterAwareTrait;

    /**
     * DoctrineGeneratedValueAnnotationGenerator constructor.
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->setAnnotationClass('ORM\\GeneratedValue');
        $this->setParams($params);
    }

    /**
     * @return string
     */
    public function getStrategy()
    {
        return $this->getParam('strategy');
    }

    /**
     * @param string $strategy
     */
    public function setStrategy($strategy)
    {
        $this->addParam('strategy', $strategy);
    }
}
