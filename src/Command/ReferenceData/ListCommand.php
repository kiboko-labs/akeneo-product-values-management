<?php

namespace Kiboko\Component\AkeneoProductValues\Command\ReferenceData;

use Kiboko\Component\AkeneoProductValues\Builder\RuleInterface;
use Kiboko\Component\AkeneoProductValues\Command\ComposerAwareInterface;
use Kiboko\Component\AkeneoProductValues\Command\ComposerAwareTrait;
use Kiboko\Component\AkeneoProductValues\Command\FilesystemAwareInterface;
use Kiboko\Component\AkeneoProductValues\Command\FilesystemAwareTrait;
use Kiboko\Component\AkeneoProductValues\Composer\RuleCapability;
use Kiboko\Component\AkeneoProductValues\Composer\RulesRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends Command implements FilesystemAwareInterface, ComposerAwareInterface
{
    use FilesystemAwareTrait;
    use ComposerAwareTrait;

    protected function configure()
    {
        $this
            ->setName('akeneo:reference-data:list')
            ->setDescription('Lists the available reference data generation rules.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var FormatterHelper $formatterHelper */
        $formatterHelper = $this->getHelper('formatter');

        $rulesList = $this->getRules();
        array_walk($rulesList, function(RuleInterface &$current) {
            $current = sprintf('%s [%s]', $current->getName(), $current->getReferenceClass());
        });

        $output->writeln(
            $formatterHelper->formatBlock(
                $rulesList,
                'info'
            )
        );
    }

    private function getRules()
    {
        $rulesRepository = new RulesRepository(
            $this->getComposer(),
            $this->getComposer()->getPluginManager()
        );

        return $rulesRepository->findAll();
    }
}
