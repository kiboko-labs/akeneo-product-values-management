<?php

namespace Kiboko\Component\AkeneoProductValues\Builder;

use Kiboko\Component\AkeneoProductValues\CodeGenerator\ProductValueCodeGenerator;
use Kiboko\Component\AkeneoProductValues\Filesystem\FileInfo;
use Kiboko\Component\AkeneoProductValues\Filesystem\FilesystemIterator;
use Kiboko\Component\AkeneoProductValues\Visitor\ClassDiscoveryVisitor;
use League\Flysystem\Filesystem;
use PhpParser\Builder;
use PhpParser\Builder\Class_;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Yaml;

class BundleBuilder
{
    /**
     * @var FileDeclarationRepository
     */
    private $fileDeclarationRepository;

    /**
     * @var array
     */
    private $configDefinitions;

    /**
     * BundleBuilder constructor.
     */
    public function __construct()
    {
        $this->fileDeclarationRepository = new FileDeclarationRepository(
            new ClassDiscoveryVisitor()
        );
        $this->configDefinitions = [];
    }

    /**
     * @param Filesystem $filesystem
     * @param string $rootPath
     */
    public function initialize(Filesystem $filesystem, $rootPath)
    {
        $iterator = new FilesystemIterator($filesystem, $rootPath);
        $phpParser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $yamlParser = new Yaml();

        $this->aggregateClasses($filesystem, $iterator, $rootPath, $phpParser);
        $this->aggregateConfigs($filesystem, $iterator, $rootPath, $yamlParser);
    }

    /**
     * @param Filesystem $filesystem
     * @param string $rootPath
     */
    public function generate(Filesystem $filesystem, $rootPath)
    {
        $prettyPrinter = new PrettyPrinter\Standard();

        foreach ($this->fileDeclarationRepository->findAll() as $filePath => $nodes) {
            $filesystem->createDir(dirname($rootPath . '/' . $filePath));

            $filesystem->put(
                $rootPath . '/' . $filePath, $prettyPrinter->prettyPrintFile($nodes)
            );
        }

        $yamlDumper = new Dumper();

        foreach ($this->configDefinitions as $filePath => $config) {
            $filesystem->createDir(dirname($rootPath . '/' . $filePath));

            $filesystem->put(
                $rootPath . '/' . $filePath, $yamlDumper->dump($config, 5, 0)
            );
        }
    }

    /**
     * @param Filesystem $filesystem
     * @param \RecursiveIterator $iterator
     * @param Parser $parser
     */
    private function aggregateClasses(Filesystem $filesystem, \RecursiveIterator $iterator, $rootPath, Parser $parser)
    {
        /** @var FileInfo $file */
        foreach ($iterator as $file) {
            if ($file->isDir()) {
                $this->aggregateClasses($filesystem, $file->getIterator(), $rootPath, $parser);
                continue;
            }

            if (!preg_match('#\.php$#', $file->getPath())) {
                continue;
            }

            $root = $parser->parse($filesystem->read($file->getPath()));

            $this->fileDeclarationRepository->add(
                preg_replace('#^' . preg_quote($rootPath) . '/?#', '', $file->getPath()),
                $root
            );
        }
    }

    /**
     * @param Filesystem $filesystem
     * @param \RecursiveIterator $iterator
     * @param Yaml $parser
     */
    private function aggregateConfigs(Filesystem $filesystem, \RecursiveIterator $iterator, $rootPath, Yaml $parser)
    {
        /** @var FileInfo $file */
        foreach ($iterator as $file) {
            if ($file->isDir()) {
                $this->aggregateConfigs($filesystem, $file->getIterator(), $rootPath, $parser);
                continue;
            }

            if (!preg_match('#\.ya?ml$#', $file->getPath())) {
                continue;
            }

            $root = $parser->parse($filesystem->read($file->getPath()));
            $this->setConfigFile(preg_replace('#^' . preg_quote($rootPath) . '#', '', $file->getPath()), $root);
        }
    }

    /**
     * @param RuleInterface $rule
     */
    public function registerRule(RuleInterface $rule)
    {
        $rule->applyTo($this);
    }

    /**
     * @param string $classFQN
     * @param NodeVisitor[] $visitors
     */
    public function visitClassDefinition($classFQN, array $visitors)
    {
        $traverser = new NodeTraverser();
        foreach ($visitors as $visitor) {
            $traverser->addVisitor($visitor);
        }

        if (($classDefinition = $this->fileDeclarationRepository->findOneClassByName($classFQN)) === null) {
            throw new \RuntimeException(sprintf(
                'The class %s is not yet defined, please define the class before trying to modify it.',
                $classFQN
            ));
        }

        $treatedNodeList = $traverser->traverse(
            [
                $classDefinition
            ]
        );

        foreach ($treatedNodeList as $classDefinition) {
            if (!$classDefinition instanceof Node\Stmt\Class_) {
                continue;
            }

            if ($classDefinition->name instanceof Node\Name) {
                $newFQCN = $classDefinition->name->toString();
            } else {
                $newFQCN = $classDefinition->name;
            }
            $this->fileDeclarationRepository->replace($newFQCN, $classDefinition);
        }
    }

    /**
     * @param string $classFQN
     * @param string $filename
     * @param Builder $builder
     */
    public function ensureClassExists($classFQN, $filename, Builder $builder)
    {
        if ($this->fileDeclarationRepository->findOneClassByName($classFQN) !== null) {
            return;
        }

        $this->fileDeclarationRepository->add($filename, [$builder->getNode()]);
    }

    /**
     * @param string $path
     * @param string $classFQN
     * @param Node[] $definition
     */
    public function mergeClassDefinition($path, $classFQN, array $definition)
    {
        $existingDefinition = $this->fileDeclarationRepository->findOneClassByName($classFQN);
        if ($existingDefinition === null) {
            $this->fileDeclarationRepository->add($path, $definition);
            return;
        }

        throw new \RuntimeException('Class merge is not yet implemented.');
    }

    /**
     * @param $filePath
     * @param array $definition
     */
    private function setConfigFile($filePath, array $definition)
    {
        $this->configDefinitions[$filePath] = $definition;
    }

    /**
     * @param $filePath
     * @param array $definition
     */
    private function mergeConfigFile($filePath, array $definition)
    {
        if (isset($this->configDefinitions[$filePath])) {

        }
        $this->configDefinitions[$filePath] = $definition;
    }
}
