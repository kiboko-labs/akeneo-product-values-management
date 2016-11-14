<?php

namespace Kiboko\Component\AkeneoProductValues\Builder;

use Composer\Composer;

interface RuleInterface
{
    /**
     * @param BundleBuilder $builder
     */
    public function applyTo(BundleBuilder $builder);

    /**
     * @return Composer
     */
    public function getComposer();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getReferenceClass();
}
