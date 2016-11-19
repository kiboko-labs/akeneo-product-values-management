<?php

namespace Kiboko\Component\AkeneoProductValues\Composer;

use Composer\Composer;
use Composer\Plugin\Capability\Capability;
use Kiboko\Component\AkeneoProductValues\Builder\RuleInterface;

interface RuleCapability extends Capability
{
    /**
     * @param Composer $composer
     * @return RuleInterface[]
     */
    public function getRules(Composer $composer);
}
