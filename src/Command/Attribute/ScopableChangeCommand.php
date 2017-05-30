<?php

namespace Kiboko\Component\AkeneoProductValues\Command\Attribute;

use Kiboko\Component\AkeneoProductValues\Command\ComposerAwareInterface;
use Kiboko\Component\AkeneoProductValues\Command\ComposerAwareTrait;
use Kiboko\Component\AkeneoProductValues\Command\FilesystemAwareInterface;
use Kiboko\Component\AkeneoProductValues\Command\FilesystemAwareTrait;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Debug\Debug;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ScopableChangeCommand extends Command implements FilesystemAwareInterface, ComposerAwareInterface, ContainerAwareInterface
{
    use FilesystemAwareTrait;
    use ComposerAwareTrait;
    use ContainerAwareTrait;

    protected function configure()
    {
        $this
            ->setName('akeneo:attribute:scopable')
            ->setDescription('Changes the scoping configuration of an attribute')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        require_once $this->getComposer()->getConfig()->.'/AppKernel.php';

        $input = new ArgvInput();
        $env = $input->getParameterOption(['--env', '-e'], getenv('SYMFONY_ENV') ?: 'dev');
        $behat = strpos($env, 'behat');
        $debug = getenv('SYMFONY_DEBUG') !== '0' && !$input->hasParameterOption(array('--no-debug', ''))
            && $env !== 'prod' && false === $behat;

        if ($debug) {
            Debug::enable();
        }

        $kernel = new \AppKernel($env, $debug);
        $application = new Application($kernel);

        $application->getKernel()->getContainer()->
    }
}
