<?php

namespace Kiboko\Component\AkeneoProductValues;

use Symfony\Component\Console\Command\Command;

class AddCommand extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('product-values:create')

            // the short description shown while running "php bin/console list"
            ->setDescription('Creates new users.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command allows you to create users...")
        ;
    }
}
