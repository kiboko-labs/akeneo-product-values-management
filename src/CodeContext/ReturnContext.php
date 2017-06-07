<?php

declare(strict_types=1);

namespace Kiboko\Component\AkeneoProductValues\CodeContext;

class ReturnContext
{
    /**
     * @var ClassReferenceContext
     */
    private $type;

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
     * @param ClassReferenceContext $type
     * @param bool $isNullable
     * @param bool $isArray
     */
    public function __construct(
        ClassReferenceContext $type,
        bool $isNullable = false,
        bool $isArray = false
    ) {
        $this->type = $type;
        $this->isNullable = $isNullable;
        $this->isArray = $isArray;
    }

    /**
     * @return ClassReferenceContext
     */
    public function getType(): ClassReferenceContext
    {
        return $this->type;
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
