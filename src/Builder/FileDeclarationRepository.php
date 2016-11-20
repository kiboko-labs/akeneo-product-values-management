<?php


namespace Kiboko\Component\AkeneoProductValues\Builder;

use Kiboko\Component\AkeneoProductValues\Visitor\ClassDiscoveryVisitor;
use Kiboko\Component\AkeneoProductValues\Visitor\ClassReplacementVisitor;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeTraverserInterface;

class FileDeclarationRepository
{
    /**
     * @var NodeTraverserInterface
     */
    private $discoveryTraverser;

    /**
     * @var NodeTraverserInterface
     */
    private $replacementTraverser;

    /**
     * @var ClassDiscoveryVisitor
     */
    private $classDiscoveryVisitor;

    /**
     * @var ClassDiscoveryVisitor
     */
    private $classReplacementVisitor;

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
     * @param ClassReplacementVisitor $classReplacementVisitor
     */
    public function __construct(
        ClassDiscoveryVisitor $classDiscoveryVisitor,
        ClassReplacementVisitor $classReplacementVisitor
    ) {
        $this->classIndex = [];
        $this->fileDeclarations = [];
        $this->classDeclarations = [];

        $this->classDiscoveryVisitor = $classDiscoveryVisitor;
        $this->classReplacementVisitor = $classReplacementVisitor;

        $this->discoveryTraverser = new NodeTraverser();
        $this->discoveryTraverser->addVisitor($classDiscoveryVisitor);

        $this->replacementTraverser = new NodeTraverser();
        $this->replacementTraverser->addVisitor($classReplacementVisitor);
    }

    /**
     * @param string $path
     * @param Node[] $declaration
     */
    public function add($path, array $declaration)
    {
        $this->fileDeclarations[$path] = $declaration;

        $this->discoveryTraverser->traverse($declaration);

        $discovered = $this->classDiscoveryVisitor->dump();
        $this->classDeclarations += $discovered;
        $this->classIndex += array_combine(array_keys($discovered), array_pad([], count($discovered), $path));
    }

    /**
     * @param string $classFQN
     * @param Node[] $declaration
     */
    public function replaceClass($classFQN, array $declaration)
    {
        if (!isset($this->classIndex[$classFQN])) {
            throw new \RuntimeException(sprintf(
                'The class %s was not found in the index.',
                $classFQN
            ));
        }

        $path = $this->classIndex[$classFQN];
        $currentDeclaration = $this->fileDeclarations[$path];

        $this->classReplacementVisitor->setClassDeclaration($declaration);
        $this->classReplacementVisitor->setClassFQN($classFQN);

        $this->fileDeclarations[$path] = $this->replacementTraverser->traverse($currentDeclaration);
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
