<?php

namespace Kiboko\Component\AkeneoProductValues\Composer;

use Composer\Command\BaseCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DecoratedCommand extends BaseCommand
{
    /**
     * @var Command
     */
    private $decorated;

    /**
     * DecoratedCommand constructor.
     *
     * @param Command $decorated
     */
    public function __construct(Command $decorated)
    {
        $this->decorated = $decorated;

        parent::__construct($decorated->getName());

        $this->setName($decorated->getName())
            ->setAliases($decorated->getAliases())
            ->setHelp($decorated->getHelp())
            ->setDescription($decorated->getDescription())
            ->setDefinition($decorated->getDefinition())
            ->setHelperSet($decorated->getHelperSet());
    }

    public function ignoreValidationErrors()
    {
        parent::ignoreValidationErrors();
        $this->decorated->ignoreValidationErrors();
        return $this;
    }

    public function setApplication(Application $application = null)
    {
        parent::setApplication($application);
        $this->decorated->setApplication($application);
        return $this;
    }

    public function setHelperSet(HelperSet $helperSet)
    {
        parent::setHelperSet($helperSet);
        $this->decorated->setHelperSet($helperSet);
        return $this;
    }

    public function isEnabled()
    {
        return parent::isEnabled() && $this->decorated->isEnabled();
    }

    protected function configure()
    {
        parent::configure();
        $this->decorated->configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->decorated->execute($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $this->decorated->interact($input, $output);
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        $this->decorated->run($input, $output);
    }

    public function setCode(callable $code)
    {
        parent::setCode($code);
        $this->decorated->setCode($code);
        return $this;
    }

    public function mergeApplicationDefinition($mergeArgs = true)
    {
        parent::mergeApplicationDefinition($mergeArgs);
        $this->decorated->mergeApplicationDefinition($mergeArgs);
    }

    public function setDefinition($definition)
    {
        parent::setDefinition($definition);
        return $this->decorated->setDefinition($definition);
    }

    public function getDefinition()
    {
        return $this->decorated->getDefinition();
    }

    public function getNativeDefinition()
    {
        return $this->decorated->getNativeDefinition();
    }

    public function addArgument($name, $mode = null, $description = '', $default = null)
    {
        parent::addArgument($name, $mode, $description, $default);
        $this->decorated->addArgument($name, $mode, $description, $default);
        return $this;
    }

    public function addOption($name, $shortcut = null, $mode = null, $description = '', $default = null)
    {
        parent::addOption($name, $shortcut, $mode, $description, $default);
        $this->decorated->setName($name, $shortcut, $mode, $description, $default);
        return $this;
    }

    public function setName($name)
    {
        parent::setName($name);
        $this->decorated->setName($name);
        return $this;
    }

    public function setProcessTitle($title)
    {
        parent::setProcessTitle($title);
        $this->decorated->setProcessTitle($title);
        return $this;
    }

    public function getName()
    {
        return $this->decorated->getName();
    }

    public function setDescription($description)
    {
        parent::setDescription($description);
        $this->decorated->setDescription($description);
        return $this;
    }

    public function getDescription()
    {
        return $this->decorated->getDescription();
    }

    public function setHelp($help)
    {
        parent::setHelp($help);
        $this->decorated->setHelp($help);
        return $this;
    }

    public function getHelp()
    {
        return $this->decorated->getHelp();
    }

    public function getProcessedHelp()
    {
        return $this->decorated->getProcessedHelp();
    }

    public function setAliases($aliases)
    {
        parent::setAliases($aliases);
        $this->decorated->setAliases($aliases);
        return $this;
    }

    public function getAliases()
    {
        return $this->decorated->getAliases();
    }

    public function getSynopsis($short = false)
    {
        return $this->decorated->getSynopsis($short);
    }

    public function addUsage($usage)
    {
        parent::addUsage($usage);
        $this->decorated->addUsage($usage);
        return $this;
    }

    public function getUsages()
    {
        return $this->decorated->getUsages();
    }

    public function getHelper($name)
    {
        return $this->decorated->getHelper($name);
    }
}
