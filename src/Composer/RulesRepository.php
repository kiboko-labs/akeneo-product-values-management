<?php

namespace Kiboko\Component\AkeneoProductValues\Composer;

use Composer\Composer;
use Composer\Plugin\PluginManager;
use Kiboko\Component\AkeneoProductValues\Builder\RuleInterface;

class RulesRepository
{
    /**
     * @var PluginManager
     */
    private $manager;

    /**
     * @var Composer
     */
    private $composer;

    /**
     * RulesRepository constructor.
     *
     * @param PluginManager $manager
     * @param Composer $composer
     */
    public function __construct(Composer $composer, PluginManager $manager)
    {
        $this->manager = $manager;
        $this->composer = $composer;
    }

    /**
     * @return RuleInterface[]
     */
    public function findAll()
    {
        /** @var RuleCapability[] $capabilities */
        $capabilities = $this->manager->getPluginCapabilities(RuleCapability::class);

        /** @var RuleInterface[] $rules */
        $rules = [];
        foreach ($capabilities as $capability) {
            $rules += $capability->getRules($this->composer);
        }

        return $rules;
    }

    /**
     * @return RuleInterface|null
     */
    public function findOneByName($name)
    {
        foreach ($this->findAll() as $rule) {
            if ($rule->getName() === $name) {
                return $rule;
            }
        }

        return null;
    }
}
