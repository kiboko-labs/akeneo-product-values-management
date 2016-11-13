<?php

namespace Kiboko\Component\AkeneoProductValues\Command\ReferenceData;

use Symfony\Component\Console\Command\Command;

class AddCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('akeneo:reference-data:add')
            ->setDescription('Adds a reference data to your AppBundle\'s ProductValue')
        ;
    }
}
