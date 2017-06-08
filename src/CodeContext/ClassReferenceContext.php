<?php

declare(strict_types=1);

namespace Kiboko\Component\AkeneoProductValues\CodeContext;

use Kiboko\Component\AkeneoProductValues\Helper\ClassName;

class ClassReferenceContext
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $alias;

    /**
     * ClassReferenceContext constructor.
     *
     * @param string $name
     * @param string $alias
     */
    public function __construct(
        string $name,
        string $alias = null
    ) {
        $this->name = $name;
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
        return ClassName::isScalar($this->name);
    }

    /**
     * @return string|null
     */
    public function isAliased(): ?string
    {
        return ClassName::isAliased($this->name);
    }
}
