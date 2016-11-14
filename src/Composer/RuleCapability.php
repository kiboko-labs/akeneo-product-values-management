<?php

namespace Kiboko\Component\AkeneoProductValues\Composer;

use Composer\Composer;
use Composer\Plugin\Capability\Capability;

interface RuleCapability extends Capability
{
    public function getRules(Composer $composer);
}
