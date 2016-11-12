<?php


namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

class DoctrineMappedSuperClassAnnotationGenerator implements AnnotationGeneratorInterface
{
    use DoctrineAnnotationGeneratorTrait;

    /**
     * @return string
     */
    public function getRepositoryClass()
    {
        return $this->getParam('repositoryClass');
    }

    /**
     * @param string $repositoryClass
     */
    public function setRepositoryClass($repositoryClass)
    {
        $this->addParam('repositoryClass', $repositoryClass);
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

        return '@ORM\\MappedSuperClass('.implode(',', $options).')';
    }
}
