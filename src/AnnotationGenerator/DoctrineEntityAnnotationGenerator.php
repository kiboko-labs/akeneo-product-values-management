<?php


namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

class DoctrineEntityAnnotationGenerator implements AnnotationGeneratorInterface
{
    use DoctrineAnnotationGeneratorTrait;

    /**
     * DoctrineEntityAnnotationGenerator constructor.
     *
     * @param string $className
     * @param string $namespace
     * @param array $params
     */
    public function __construct($className, $namespace, array $params = [])
    {
        $this->setParams($params + ['type' => $namespace . '\\' . $className]);
    }

    /**
     * @return string
     */
    public function getEntityClass()
    {
        return $this->getParam('entityClass');
    }

    /**
     * @param string $entityClass
     */
    public function setEntityClass($entityClass)
    {
        $this->addParam('entityClass', $entityClass);
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

        return '@ORM\\Entity('.implode(',', $options).')';
    }
}
