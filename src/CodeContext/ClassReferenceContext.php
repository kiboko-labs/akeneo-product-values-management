<?php

declare(strict_types=1);

namespace Kiboko\Component\AkeneoProductValues\CodeContext;

use Kiboko\Component\AkeneoProductValues\Helper\ClassName;

class ClassReferenceContext
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $alias;

    /**
     * ClassReferenceContext constructor.
     *
     * @param string $className
     * @param string $alias
     */
    public function __construct(
        string $className,
        string $alias = null
    ) {
        $this->className = $className;
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * @return bool
     */
    public function isScalar(): bool
    {
        return ClassName::isScalar($this->className);
    }

    /**
     * @return string|null
     */
    public function isAliased(): ?string
    {
        return ClassName::isAliased($this->className);
    }
}
