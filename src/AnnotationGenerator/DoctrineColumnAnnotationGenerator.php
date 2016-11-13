<?php


namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

class DoctrineColumnAnnotationGenerator implements AnnotationGeneratorInterface
{
    use DoctrineAnnotationGeneratorTrait;

    /**
     * DoctrineColumnAnnotationGenerator constructor.
     *
     * @param string $type
     * @param array $params
     */
    public function __construct($type, array $params = [])
    {
        $this->setParams($params + ['type' => $type]);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getParam('type');
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->addParam('type', $type);
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->getParam('length');
    }

    /**
     * @param int $length
     */
    public function setLength($length)
    {
        $this->addParam('length', $length);
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
     * @return int
     */
    public function getPrecision()
    {
        return $this->getParam('precision');
    }

    /**
     * @param int $precision
     */
    public function setPrecision($precision)
    {
        $this->addParam('precision', $precision);
    }

    /**
     * @return int
     */
    public function getScale()
    {
        return $this->getParam('scale');
    }

    /**
     * @param int $scale
     */
    public function setScale($scale)
    {
        $this->addParam('scale', $scale);
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
        if (!in_array($option, ['default', 'unsigned', 'fixed', 'comment', 'collection'])) {
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

        return '@ORM\\Column('.implode(',', $options).')';
    }
}
