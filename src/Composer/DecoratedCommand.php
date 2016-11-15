<?php

namespace Kiboko\Component\AkeneoProductValues\Composer;

use Composer\Command\BaseCommand;
use Composer\Composer;
use Kiboko\Component\AkeneoProductValues\Command\ComposerAwareInterface;
use Kiboko\Component\AkeneoProductValues\Command\FilesystemAwareInterface;
use Kiboko\Component\AkeneoProductValues\Command\FilesystemAwareTrait;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DecoratedCommand extends BaseCommand implements FilesystemAwareInterface
{
    use FilesystemAwareTrait;

    /**
     * @var Command
     */
    private $decorated;

    /**
     * DecoratedCommand constructor.
     *
     * @param Command $decorated
     * @param Filesystem $filesystem
     */
    public function __construct(Command $decorated, Filesystem $filesystem, Composer $composer)
    {
        $this->decorated = $decorated;

        parent::__construct($decorated->getName());

        $this
            ->setComposer($composer)
            ->setName($decorated->getName())
            ->setAliases($decorated->getAliases())
            ->setHelp($decorated->getHelp())
            ->setDescription($decorated->getDescription())
            ->setDefinition($decorated->getDefinition())
        ;

        if ($this->decorated instanceof FilesystemAwareInterface) {
            $this->decorated->setFilesystem($filesystem);
        }
        if ($this->decorated instanceof ComposerAwareInterface) {
            $this->decorated->setComposer($composer);
        }
    }

    public function setComposer(Composer $composer)
    {
        parent::setComposer($composer);

        if ($this->decorated instanceof ComposerAwareInterface) {
            $this->decorated->setComposer($composer);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function ignoreValidationErrors()
    {
        parent::ignoreValidationErrors();
        $this->decorated->ignoreValidationErrors();
        return $this;
    }

    /**
     * @param Application|null $application
     * @return $this
     */
    public function setApplication(Application $application = null)
    {
        parent::setApplication($application);
        $this->decorated->setApplication($application);
        return $this;
    }

    /**
     * @param HelperSet $helperSet
     * @return $this
     */
    public function setHelperSet(HelperSet $helperSet)
    {
        parent::setHelperSet($helperSet);
        $this->decorated->setHelperSet($helperSet);
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return parent::isEnabled() && $this->decorated->isEnabled();
    }

    /**
     *
     */
    protected function configure()
    {
        parent::configure();
        $this->decorated->configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->decorated->execute($input, $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $this->decorated->interact($input, $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        $this->decorated->run($input, $output);
    }

    /**
     * @param callable $code
     * @return $this
     */
    public function setCode($code)
    {
        parent::setCode($code);
        $this->decorated->setCode($code);
        return $this;
    }

    /**
     * @param bool $mergeArgs
     */
    public function mergeApplicationDefinition($mergeArgs = true)
    {
        parent::mergeApplicationDefinition($mergeArgs);
        $this->decorated->mergeApplicationDefinition($mergeArgs);
    }

    /**
     * @param array|\Symfony\Component\Console\Input\InputDefinition $definition
     * @return Command
     */
    public function setDefinition($definition)
    {
        parent::setDefinition($definition);
        return $this->decorated->setDefinition($definition);
    }

    /**
     * @return \Symfony\Component\Console\Input\InputDefinition
     */
    public function getDefinition()
    {
        return $this->decorated->getDefinition();
    }

    /**
     * @return \Symfony\Component\Console\Input\InputDefinition
     */
    public function getNativeDefinition()
    {
        return $this->decorated->getNativeDefinition();
    }

    /**
     * @param string $name
     * @param null $mode
     * @param string $description
     * @param null $default
     * @return $this
     */
    public function addArgument($name, $mode = null, $description = '', $default = null)
    {
        parent::addArgument($name, $mode, $description, $default);
        $this->decorated->addArgument($name, $mode, $description, $default);
        return $this;
    }

    /**
     * @param string $name
     * @param null $shortcut
     * @param null $mode
     * @param string $description
     * @param null $default
     * @return $this
     */
    public function addOption($name, $shortcut = null, $mode = null, $description = '', $default = null)
    {
        parent::addOption($name, $shortcut, $mode, $description, $default);
        $this->decorated->setName($name, $shortcut, $mode, $description, $default);
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        parent::setName($name);
        $this->decorated->setName($name);
        return $this;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setProcessTitle($title)
    {
        parent::setProcessTitle($title);
        $this->decorated->setProcessTitle($title);
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->decorated->getName();
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        parent::setDescription($description);
        $this->decorated->setDescription($description);
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->decorated->getDescription();
    }

    /**
     * @param string $help
     * @return $this
     */
    public function setHelp($help)
    {
        parent::setHelp($help);
        $this->decorated->setHelp($help);
        return $this;
    }

    /**
     * @return string
     */
    public function getHelp()
    {
        return $this->decorated->getHelp();
    }

    /**
     * @return string
     */
    public function getProcessedHelp()
    {
        return $this->decorated->getProcessedHelp();
    }

    /**
     * @param \string[] $aliases
     * @return $this
     */
    public function setAliases($aliases)
    {
        parent::setAliases($aliases);
        $this->decorated->setAliases($aliases);
        return $this;
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return $this->decorated->getAliases();
    }

    /**
     * @param bool $short
     * @return string
     */
    public function getSynopsis($short = false)
    {
        return $this->decorated->getSynopsis($short);
    }

    /**
     * @param string $usage
     * @return $this
     */
    public function addUsage($usage)
    {
        parent::addUsage($usage);
        $this->decorated->addUsage($usage);
        return $this;
    }

    /**
     * @return array
     */
    public function getUsages()
    {
        return $this->decorated->getUsages();
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getHelper($name)
    {
        return $this->decorated->getHelper($name);
    }
}
