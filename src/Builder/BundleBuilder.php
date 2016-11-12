<?php

namespace Kiboko\Component\AkeneoProductValues\Builder;

use Kiboko\Component\AkeneoProductValues\Filesystem\FileInfo;
use Kiboko\Component\AkeneoProductValues\Filesystem\FilesystemIterator;
use League\Flysystem\File;
use League\Flysystem\Filesystem;
use PhpParser\Builder\Class_;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;

class BundleBuilder
{
    /**
     * @var Class_[]
     */
    private $fileDefinitions;

    /**
     * BundleBuilder constructor.
     */
    public function __construct()
    {
        $this->fileDefinitions = [];
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
     * @param Filesystem $filesystem
     * @param string $rootPath
     */
    public function initialize(Filesystem $filesystem, $rootPath)
    {
        $iterator = new FilesystemIterator($filesystem, $rootPath);
        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $this->aggregateClasses($filesystem, $iterator, $rootPath, $parser);
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

            $root = $parser->parse($filesystem->read($file->getPath()));
            $this->setFileDefinition(preg_replace('#^' . preg_quote($rootPath) . '#', '', $file->getPath()), $root);
        }
    }
}
