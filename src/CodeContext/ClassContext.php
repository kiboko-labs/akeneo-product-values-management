<?php

declare(strict_types=1);

namespace Kiboko\Component\AkeneoProductValues\CodeContext;

class ClassContext implements NamedContextInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var ClassReferenceContext
     */
    private $parentClass;

    /**
     * @var ClassReferenceContext[]
     */
    private $implementedInterfaces;

    /**
     * @var ClassReferenceContext[]
     */
    private $usedTraits;

    /**
     * ClassContext constructor.
     * @param string $name
     * @param ClassReferenceContext $parentClass
     * @param ClassReferenceContext[] $implementedInterfaces
     * @param ClassReferenceContext[] $usedTraits
     */
    public function __construct(
        string $name,
        ?ClassReferenceContext $parentClass = null,
        array $implementedInterfaces = [],
        array $usedTraits = []
    ) {
        $this->setName($name);
        $this->setParentClass($parentClass);

        $this->implementedInterfaces = [];
        foreach ($implementedInterfaces as $implementedInterface) {
            $this->addImplementedInterface($implementedInterface);
        }

        $this->usedTraits = [];
        foreach ($usedTraits as $usedTrait) {
            $this->addUsedTrait($usedTrait);
        }
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param ClassReferenceContext $parentClass
     */
    public function setParentClass(?ClassReferenceContext $parentClass): void
    {
        $this->parentClass = $parentClass;
    }

    /**
     * @return ClassReferenceContext
     */
    public function getParentClass(): ?ClassReferenceContext
    {
        return $this->parentClass;
    }

    /**
     * @param ClassReferenceContext[] $implementedInterfaces
     */
    public function setImplementedInterfaces(array $implementedInterfaces): void
    {
        $this->implementedInterfaces = [];

        foreach ($implementedInterfaces as $implementedInterface) {
            $this->addImplementedInterface($implementedInterface);
        }
    }

    /**
     * @param ClassReferenceContext $implementedInterface
     */
    public function addImplementedInterface(ClassReferenceContext $implementedInterface): void
    {
        if (in_array($implementedInterface, $this->implementedInterfaces)) {
            return;
        }

        $this->implementedInterfaces[] = $implementedInterface;
    }

    /**
     * @param ClassReferenceContext $implementedInterface
     */
    public function removeImplementedInterface(ClassReferenceContext $implementedInterface): void
    {
        $key = array_search($implementedInterface, $this->implementedInterfaces);
        if ($key === false) {
            return;
        }

        unset($this->implementedInterfaces[$key]);
    }

    /**
     * @return ClassReferenceContext[]
     */
    public function getImplementedInterfaces(): array
    {
        return $this->implementedInterfaces;
    }

    /**
     * @param ClassReferenceContext[] $usedTraits
     */
    public function setUsedTraits(array $usedTraits): void
    {
        $this->usedTraits = [];

        foreach ($usedTraits as $usedTrait) {
            $this->addUsedTrait($usedTrait);
        }
    }

    /**
     * @param ClassReferenceContext $usedTrait
     */
    public function addUsedTrait(ClassReferenceContext $usedTrait): void
    {
        if (in_array($usedTrait, $this->usedTraits)) {
            return;
        }

        $this->usedTraits[] = $usedTrait;
    }

    /**
     * @param ClassReferenceContext $usedTrait
     */
    public function removeUsedTrait(ClassReferenceContext $usedTrait): void
    {
        $key = array_search($usedTrait, $this->usedTraits);
        if ($key === false) {
            return;
        }

        unset($this->usedTraits[$key]);
    }

    /**
     * @return ClassReferenceContext[]
     */
    public function getUsedTraits(): array
    {
        return $this->usedTraits;
    }
}
