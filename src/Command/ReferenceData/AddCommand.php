<?php

namespace Kiboko\Component\AkeneoProductValues\Command\ReferenceData;

use Kiboko\Component\AkeneoProductValues\Command\FilesystemAwareInterface;
use Kiboko\Component\AkeneoProductValues\Command\FilesystemAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddCommand extends Command implements FilesystemAwareInterface
{
    use FilesystemAwareTrait;

    protected function configure()
    {
        $this
            ->setName('akeneo:reference-data:add')
            ->setDescription('Adds a reference data to your AppBundle\'s ProductValue')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}
