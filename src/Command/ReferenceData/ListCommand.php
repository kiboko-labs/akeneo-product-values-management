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
        $rulesRepository = new RulesRepository(
            $this->getComposer(),
            $this->getComposer()->getPluginManager()
        );

        $this->formatRulesList($rulesRepository, $input, $output);
    }

    /**
     * @param RulesRepository $rulesRepository
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function formatRulesList(RulesRepository $rulesRepository, InputInterface $input, OutputInterface $output)
    {
        /** @var FormatterHelper $formatterHelper */
        $formatterHelper = $this->getHelper('formatter');

        $rules = [];
        foreach ($rulesRepository->findAll() as $rule) {
            $rules[] = sprintf(
                '%s (%s), class %s',
                $rule->getName(),
                $rule->getType(),
                $rule->getReferenceClass()
            );
        }

        $output->writeln(
            $formatterHelper->formatBlock(
                array_merge(
                    [
                        'Available reference datas:'
                    ],
                    $rules
                ), 'info')
        );
    }
}
