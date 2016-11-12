<?php


namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

class DoctrineGeneratedValueAnnotationGenerator implements AnnotationGeneratorInterface
{
    use DoctrineAnnotationGeneratorTrait;

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
