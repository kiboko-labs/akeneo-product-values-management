<?php


namespace Kiboko\Component\AkeneoProductValues\Builder;

use Kiboko\Component\AkeneoProductValues\Visitor\ClassDiscoveryVisitor;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeTraverserInterface;

class FileDeclarationRepository
{
    /**
     * @var NodeTraverserInterface
     */
    private $traverser;

    /**
     * @var ClassDiscoveryVisitor
     */
    private $classDiscoveryVisitor;

    /**
     * @var Node[][]
     */
    private $fileDeclarations;

    /**
     * @var Node\Stmt\Class_[][]
     */
    private $classDeclarations;

    /**
     * @var string[]
     */
    private $classIndex;

    /**
     * FileDeclarationRepository constructor.
     * @param ClassDiscoveryVisitor $classDiscoveryVisitor
     */
    public function __construct(ClassDiscoveryVisitor $classDiscoveryVisitor)
    {
        $this->classIndex = [];
        $this->fileDeclarations = [];
        $this->classDeclarations = [];
        $this->traverser = new NodeTraverser();
        $this->traverser->addVisitor($classDiscoveryVisitor);

        $this->classDiscoveryVisitor = $classDiscoveryVisitor;
    }

    /**
     * @param string $path
     * @param Node[] $declaration
     */
    public function add($path, array $declaration)
    {
        $this->fileDeclarations[$path] = $declaration;

        $this->traverser->traverse($declaration);

        $discovered = $this->classDiscoveryVisitor->dump();
        $this->classDeclarations += $discovered;
        $this->classIndex += array_combine(array_keys($discovered), array_pad([], count($discovered), $path));
    }

    /**
     * @param string $classFQN
     * @return null|Node\Stmt\Class_[]
     */
    public function findOneClassByName($classFQN)
    {
        if (!isset($this->classDeclarations[$classFQN])) {
            return null;
        }

        return $this->classDeclarations[$classFQN];
    }

    public function findAll()
    {
        return $this->fileDeclarations;
    }
}
