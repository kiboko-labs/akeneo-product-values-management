<?php

namespace Kiboko\Component\AkeneoProductValues\Builder;

use Kiboko\Component\AkeneoProductValues\Filesystem\FileInfo;
use Kiboko\Component\AkeneoProductValues\Filesystem\FilesystemIterator;
use League\Flysystem\Filesystem;
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
     * @var Class_[]
     */
    private $fileDefinitions;

    /**
     * @var array
     */
    private $configDefinitions;

    /**
     * BundleBuilder constructor.
     */
    public function __construct()
    {
        $this->fileDefinitions = [];
        $this->configDefinitions = [];
    }

    /**
     * @param string $filePath
     * @param Node[] $definition
     */
    public function setFileDefinition($filePath, array $definition)
    {
        $this->fileDefinitions[$filePath] = $definition;
    }

    /**
     * @param string $filePath
     * @param NodeVisitor[] $visitors
     */
    public function visitFileDefinition($filePath, array $visitors)
    {
        $traverser = new NodeTraverser();
        foreach ($visitors as $visitor) {
            $traverser->addVisitor($visitor);
        }

        $traverser->traverse($this->fileDefinitions[$filePath]);
    }

    /**
     * @param $filePath
     * @param array $definition
     */
    public function setConfigFile($filePath, array $definition)
    {
        $this->configDefinitions[$filePath] = $definition;
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

        foreach ($this->fileDefinitions as $filePath => $nodes) {
            $filesystem->createDir(dirname($rootPath . '/' . $filePath));

            $filesystem->put(
                $rootPath . '/' . $filePath, $prettyPrinter->prettyPrintFile($nodes)
            );
        }

        $yamlDumper = new Dumper();

        foreach ($this->configDefinitions as $filePath => $config) {
            $filesystem->createDir(dirname($rootPath . '/' . $filePath));

            $filesystem->put(
                $rootPath . '/' . $filePath, $yamlDumper->dump($config, 5, 0, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK)
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
            $this->setFileDefinition(preg_replace('#^' . preg_quote($rootPath) . '#', '', $file->getPath()), $root);
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
}
