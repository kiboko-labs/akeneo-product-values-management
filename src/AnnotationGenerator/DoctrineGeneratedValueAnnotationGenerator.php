<?php


namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

class DoctrineGeneratedValueAnnotationGenerator implements AnnotationGeneratorInterface
{
    use DoctrineAnnotationGeneratorTrait;

    /**
     * DoctrineGeneratedValueAnnotationGenerator constructor.
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

        return '@ORM\\GeneratedValue('.implode(',', $options).')';
    }
}
