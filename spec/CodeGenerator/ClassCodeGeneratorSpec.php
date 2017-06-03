<?php

declare(strict_types=1);

namespace spec\Kiboko\Component\AkeneoProductValues\CodeGenerator;

use Kiboko\Component\AkeneoProductValues\CodeContext\ClassContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\ClassReferenceContext;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ClassCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\FileCodeGenerator;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClassCodeGeneratorSpec extends ObjectBehavior
{
    function it_is_initializable(
        FileCodeGenerator $fileCodeGenerator,
        ClassContext $classContext
    ) {
        $this->beConstructedWith(
            $fileCodeGenerator,
            $classContext
        );

        $this->shouldHaveType(ClassCodeGenerator::class);
    }

    function it_constructs_proper_class_name(
        FileCodeGenerator $fileCodeGenerator
    ) {
        $this->beConstructedWith(
            $fileCodeGenerator,
            new ClassContext('Foo\\Bar')
        );

        $this->getNode()->shouldHaveClassName('Bar');
    }

    function it_implements_proper_interfaces(
        FileCodeGenerator $fileCodeGenerator
    ) {
        $this->beConstructedWith(
            $fileCodeGenerator,
            new ClassContext(
                'Foo\\Bar',
                null,
                [
                    new ClassReferenceContext('Foo\\BarInterface'),
                    new ClassReferenceContext('Foo\\Bar\\BazInterface'),
                    new ClassReferenceContext('\\Countable'),
                ]
            )
        );

        $this->getNode()->shouldImplementInterfaces(
            [
                'BarInterface',
                'BazInterface',
                'Countable',
            ]
        );
    }

    public function getMatchers()
    {
        return [
            'haveClassName' => function ($subject, $key) {
                $visitor = new class($key) implements NodeVisitor {
                    private $found;

                    public function __construct()
                    {
                        $this->found = [];
                    }

                    public function enterNode(Node $node)
                    {
                        if (!$node instanceof Class_) {
                            return;
                        }

                        $this->found[] = $node->name;
                    }

                    public function getFound(): array
                    {
                        return $this->found;
                    }

                    public function beforeTraverse(array $nodes) {}

                    public function leaveNode(Node $node) {}

                    public function afterTraverse(array $nodes) {}
                };

                $traverser = new NodeTraverser();
                $traverser->addVisitor($visitor);
                $traverser->traverse([$subject]);

                $found = $visitor->getFound();
                $foundCount = count($found);
                if ($foundCount < 1) {
                    throw new FailureException(sprintf(
                        'Class with name "%s" was not found.',
                        $key
                    ));
                }
                if ($foundCount > 1) {
                    throw new FailureException(sprintf(
                        'Too many classes found, expected "%s", actually found "%s".',
                        $key, implode(', ', $found)
                    ));
                }
                if ($foundCount === 1 && $found[0] !== $key) {
                    throw new FailureException(sprintf(
                        'Class with name "%s" was not found, actually found "%s".',
                        $key, $found[0]
                    ));
                }

                return true;
            },
            'implementInterfaces' => function ($subject, $key) {
                $visitor = new class implements NodeVisitor {
                    private $found;

                    public function __construct()
                    {
                        $this->found = [];
                    }

                    public function enterNode(Node $node)
                    {
                        if (!$node instanceof Class_) {
                            return;
                        }

                        $this->found = array_map(function(Node\Name $item){
                            return $item->toString();
                        }, $node->implements);
                    }

                    public function getFound(): array
                    {
                        return $this->found;
                    }

                    public function beforeTraverse(array $nodes) {}

                    public function leaveNode(Node $node) {}

                    public function afterTraverse(array $nodes) {}
                };

                $traverser = new NodeTraverser();
                $traverser->addVisitor($visitor);
                $traverser->traverse([$subject]);

                $found = $visitor->getFound();

                foreach ($found as $interface) {
                    if (!in_array($interface, $key)) {
                        throw new FailureException(sprintf(
                            'Interface with name "%s" was unexpected. Found: %s',
                            $interface, count($found) > 0 ? implode(', ', $found) : 'none'
                        ));
                    }
                }

                foreach ($key as $interface) {
                    if (!in_array($interface, $found)) {
                        throw new FailureException(sprintf(
                            'Interface with name "%s" was expected, but missing. Found: %s',
                            $interface, count($found) > 0 ? implode(', ', $found) : 'none'
                        ));
                    }
                }

                return true;
            }
        ];
    }
}
