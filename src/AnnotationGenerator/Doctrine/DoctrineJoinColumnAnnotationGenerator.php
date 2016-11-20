<?php

namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator\Doctrine;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorTrait;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\ParameterAwareTrait;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\ParameteredAnnotationGeneratorInterface;

class DoctrineJoinColumnAnnotationGenerator implements ParameteredAnnotationGeneratorInterface
{
    use AnnotationGeneratorTrait;
    use ParameterAwareTrait;

    /**
     * DoctrineJoinColumnAnnotationGenerator constructor.
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->setAnnotationClass('ORM\\JoinColumn');
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
    public function getReferencedColumnName()
    {
        return $this->getParam('referencedColumnName');
    }

    /**
     * @param string $referencedColumnName
     */
    public function setReferencedColumnName($referencedColumnName)
    {
        $this->addParam('referencedColumnName', $referencedColumnName);
    }

    /**
     * @return bool
     */
    public function getUnique()
    {
        return $this->getParam('unique');
    }

    /**
     * @param bool $unique
     */
    public function setUnique($unique)
    {
        $this->addParam('unique', (bool) $unique);
    }

    /**
     * @return bool
     */
    public function getNullable()
    {
        return $this->getParam('nullable');
    }

    /**
     * @param bool $nullable
     */
    public function setNullable($nullable)
    {
        $this->addParam('nullable', (bool) $nullable);
    }

    /**
     * @return string
     */
    public function getOnDelete()
    {
        return $this->getParam('onDelete');
    }

    /**
     * @param string $onDelete
     */
    public function setOnDelete($onDelete)
    {
        $this->addParam('onDelete', $onDelete);
    }

    /**
     * @return string
     */
    public function getColumnDefinition()
    {
        return $this->getParam('columnDefinition');
    }

    /**
     * @param string $columnDefinition
     */
    public function setColumnDefinition($columnDefinition)
    {
        $this->addParam('columnDefinition', $columnDefinition);
    }
}
