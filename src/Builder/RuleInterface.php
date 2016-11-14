<?php

namespace Kiboko\Component\AkeneoProductValues\Builder;

interface RuleInterface
{
    /**
     * @param BundleBuilder $builder
     */
    public function applyTo(BundleBuilder $builder);

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getReferenceClass();
}
