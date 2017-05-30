<?php

namespace Kiboko\Component\AkeneoProductValues\Builder;

use Composer\Composer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface RuleInterface
{
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param Composer $composer
     * @return bool
     */
    public function interact(InputInterface $input, OutputInterface $output, Composer $composer);

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
    public function getType();

    /**
     * @return string
     */
    public function getReferenceClass();
}
