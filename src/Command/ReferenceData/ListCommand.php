<?php

namespace Kiboko\Component\AkeneoProductValues\Command\ReferenceData;

use Kiboko\Component\AkeneoProductValues\Command\FilesystemAwareInterface;
use Kiboko\Component\AkeneoProductValues\Command\FilesystemAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends Command  implements FilesystemAwareInterface
{
    use FilesystemAwareTrait;

    /**
     * @var string[]
     */
    private $rules;

    /**
     * ListCommand constructor.
     * @param string $name
     * @param array $rules
     */
    public function __construct($name = null, array $rules)
    {
        parent::__construct($name);

        $this->rules = $rules;
    }

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
        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        $output->writeln(
            $formatterHelper->formatBlock(
                $this->rules,
                'info'
            )
        );
    }
}
