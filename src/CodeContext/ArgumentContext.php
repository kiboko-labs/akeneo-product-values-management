<?php

declare(strict_types=1);

namespace Kiboko\Component\AkeneoProductValues\CodeContext;

class ArgumentContext
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var ClassReferenceContext
     */
    private $type;

    /**
     * @var DefaultValueContextInterface
     */
    private $default;

    /**
     * @var bool
     */
    private $isNullable;

    /**
     * @var bool
     */
    private $isArray;

    /**
     * ArgumentCodeContext constructor.
     *
     * @param string $name
     * @param ClassReferenceContext $type
     * @param DefaultValueContextInterface|null $default
     * @param bool $isNullable
     * @param bool $isArray
     */
    public function __construct(
        string $name,
        ClassReferenceContext $type,
        ?DefaultValueContextInterface $default,
        bool $isNullable = false,
        bool $isArray = false
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->default = $default;
        $this->isNullable = $isNullable;
        $this->isArray = $isArray;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return ClassReferenceContext
     */
    public function getType(): ClassReferenceContext
    {
        return $this->type;
    }

    /**
     * @return DefaultValueContextInterface
     */
    public function getDefault(): DefaultValueContextInterface
    {
        return $this->default;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->isNullable;
    }

    /**
     * @return bool
     */
    public function isArray(): bool
    {
        return $this->isArray;
    }
}
