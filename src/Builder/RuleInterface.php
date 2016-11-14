<?php


namespace Kiboko\Component\AkeneoProductValues\Builder;

interface RuleInterface
{
    /**
     * @param BundleBuilder $builder
     */
    public function applyTo(BundleBuilder $builder);
}
