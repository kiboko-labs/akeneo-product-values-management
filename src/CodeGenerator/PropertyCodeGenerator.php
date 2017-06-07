<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationSerializer;
use Kiboko\Component\AkeneoProductValues\CodeContext\ClassReferenceContext;
use Kiboko\Component\AkeneoProductValues\Helper\ClassName;
use PhpParser\Builder;
use PhpParser\BuilderFactory;
use PhpParser\Node;

class PropertyCodeGenerator implements Builder
{
    const ACCESS_PUBLIC = 'public';
    const ACCESS_PROTECTED = 'protected';
    const ACCESS_PRIVATE = 'private';

    /**
     * @var ClassCodeGeneratorInterface
     */
    private $parentGenerator;

    /**
     * @var string
     */
    private $access;

    /**
     * @var string
     */
    private $name;

    /**
     * @var ClassReferenceContext
     */
    private $typeHint;

    /**
     * @var bool
     */
    private $isNullable;

    /**
     * @var bool
     */
    private $isArray;

    /**
     * @var bool
     */
    private $isStatic;

    /**
     * @var AnnotationGeneratorInterface[]
     */
    private $annotationGenerators;

    /**
     * PropertyCodeGenerator constructor.
     *
     * @param ClassCodeGeneratorInterface $parentGenerator
     * @param string $name
     * @param AnnotationGeneratorInterface[] $annotationGenerators
     * @param bool $isNullable
     * @param bool $isArray
     */
    public function __construct(
        ClassCodeGeneratorInterface $parentGenerator,
        string $name,
        array $annotationGenerators,
        bool $isNullable = false,
        bool $isArray = false
    ) {
        $this->parentGenerator = $parentGenerator;
        $this->name = $name;
        $this->access = self::ACCESS_PRIVATE;
        $this->annotationGenerators = $annotationGenerators;
        $this->isNullable = $isNullable;
        $this->isArray = $isArray;
    }

    /**
     * @return string
     */
    public function getAccess(): string
    {
        return $this->access;
    }

    /**
     * @param string $access
     */
    public function setAccess(string $access): void
    {
        if (!in_array($access, [self::ACCESS_PUBLIC, self::ACCESS_PROTECTED, self::ACCESS_PRIVATE])) {
            throw new \RuntimeException(sprintf('Invalid access type: %s. Should be in [%s]',
                $access, implode(',', [self::ACCESS_PUBLIC, self::ACCESS_PROTECTED, self::ACCESS_PRIVATE])
            ));
        }

        $this->access = $access;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return ClassReferenceContext
     */
    public function getTypeHint(): ClassReferenceContext
    {
        return $this->typeHint;
    }

    /**
     * @param ClassReferenceContext $typeHint
     */
    public function setTypeHint(ClassReferenceContext $typeHint): void
    {
        $this->typeHint = $typeHint;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->isNullable;
    }

    /**
     * @param bool $isNullable
     */
    public function setNullable(bool $isNullable): void
    {
        $this->isNullable = $isNullable;
    }

    /**
     * @return bool
     */
    public function isArray(): bool
    {
        return $this->isArray;
    }

    /**
     * @param bool $isArray
     */
    public function setArray(bool $isArray): void
    {
        $this->isNullable = $isArray;
    }

    /**
     * @return bool
     */
    public function isStatic(): bool
    {
        return $this->isStatic;
    }

    /**
     * @param bool $isStatic
     */
    public function setStatic(bool $isStatic)
    {
        $this->isStatic = $isStatic;
    }

    /**
     * @return Node
     */
    public function getNode()
    {
        $factory = new BuilderFactory();

        $root = $factory->property($this->name)
            ->setDocComment($this->compileDocComment());

        switch ($this->access) {
            case self::ACCESS_PUBLIC:
                $root->makePublic();
                break;

            case self::ACCESS_PROTECTED:
                $root->makePublic();
                break;

            case self::ACCESS_PRIVATE:
                $root->makePublic();
                break;
        }

        if ($this->isStatic) {
            $root->makeStatic();
        }

        return $root->getNode();
    }

    /**
     * @return string
     */
    protected function compileDocComment()
    {
        $annotationSerializer = new AnnotationSerializer();

        if (count($this->annotationGenerators) > 0) {
            return '/**' . PHP_EOL
                . ' * @var ' . ClassName::formatDocTypeHint($this->typeHint, $this->isNullable, $this->isArray) . PHP_EOL
                . ' *' . PHP_EOL
                . array_walk(
                    $this->annotationGenerators,
                    function (AnnotationGeneratorInterface $current) use ($annotationSerializer) {
                        return $annotationSerializer->serialize($current) . PHP_EOL;
                    }
                )
                . ' */';
        }

        return '/**' . PHP_EOL
            . ' * @var ' . ClassName::formatDocTypeHint($this->typeHint, $this->isNullable, $this->isArray) . PHP_EOL
            . ' */';
    }
}
