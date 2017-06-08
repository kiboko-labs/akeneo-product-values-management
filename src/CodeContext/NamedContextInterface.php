<?php

namespace Kiboko\Component\AkeneoProductValues\CodeContext;

interface NamedContextInterface extends ContextInterface
{
    /**
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * @return string
     */
    public function getName(): string;
}
