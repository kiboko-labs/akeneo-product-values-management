<?php

namespace Kiboko\Component\AkeneoProductValues\Command\Attribute;

use Kiboko\Component\AkeneoProductValues\Command\ComposerAwareInterface;
use Kiboko\Component\AkeneoProductValues\Command\ComposerAwareTrait;
use Kiboko\Component\AkeneoProductValues\Command\FilesystemAwareInterface;
use Kiboko\Component\AkeneoProductValues\Command\FilesystemAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LocalizableChangeCommand extends Command  implements FilesystemAwareInterface, ComposerAwareInterface
{
    use FilesystemAwareTrait;
    use ComposerAwareTrait;

    protected function configure()
    {
        $this
            ->setName('akeneo:attribute:localizable')
            ->setDescription('Changes the localizing configuration of an attribute')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}
