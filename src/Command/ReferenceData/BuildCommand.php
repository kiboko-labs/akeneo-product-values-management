<?php

namespace Kiboko\Component\AkeneoProductValues\Command\ReferenceData;

use Symfony\Component\Console\Command\Command;

class BuildCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('akeneo:reference-data:remove')
            ->setDescription('Removes a reference data from your AppBundle\'s ProductValue')
        ;
    }
}
