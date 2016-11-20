<?php

namespace Kiboko\Component\AkeneoProductValues\Builder;

use PhpParser\Builder\Class_;
use PhpParser\BuilderFactory;
use PhpParser\Node;

class ClassMerger
{
    const PREFER_NONE  = 0;
    const PREFER_LEFT  = 1;
    const PREFER_RIGHT = 2;

    /**
     * @var int
     */
    private $preference;

    /**
     * ClassMerger constructor.
     * @param int $preference
     */
    public function __construct($preference = self::PREFER_NONE)
    {
        if (!in_array($preference, [self::PREFER_NONE, self::PREFER_LEFT, self::PREFER_RIGHT])) {
            $preference = self::PREFER_NONE;
        }

        $this->preference = $preference;
    }

    /**
     * @param Node\Stmt\Class_ $left
     * @param Node\Stmt\Class_ $right
     * @return Node\Stmt\Class_
     */
    public function merge(Node\Stmt\Class_ $left, Node\Stmt\Class_ $right)
    {
        $factory = new BuilderFactory();

        $class = $factory->class(
            $this->chooseName($left->name, $right->name)
        );

        $this->chooseExtend($class, $left, $right);

        return $class->getNode();
    }

    private function normalizeName($name)
    {
        if (is_string($name)) {
            return $name;
        }
        if ($name instanceof Node\Name) {
            return $name->toString();
        }

        throw new \RuntimeException(sprintf(
            'Unhandled name attribute. Expected %s, got %s',
            Node\Name::class,
            is_object($name) ? get_class($name) : gettype($name)
        ));
    }

    private function prefer($left, $right)
    {
        if ($this->preference === self::PREFER_NONE) {
            throw new \RuntimeException('Could not choose between both versions, as PREFER_NONE mode is active.');
        }
        if ($this->preference === self::PREFER_LEFT) {
            return $left;
        }
        if ($this->preference === self::PREFER_RIGHT) {
            return $right;
        }
    }

    /**
     * @param string|Node\Name $left
     * @param string|Node\Name $right
     * @return Node\Name
     */
    private function chooseName($left, $right)
    {
        if ($this->normalizeName($left) === $this->normalizeName($right)) {
            if ($left === null) {
                return null;
            }

            return new Node\Name(
                $this->normalizeName($left)
            );
        }

        return new Node\Name(
            $this->normalizeName($this->prefer($left, $right))
        );
    }

    /**
     * @param Class_ $merged
     * @param Node\Stmt\Class_ $left
     * @param Node\Stmt\Class_ $right
     */
    private function chooseExtend(Class_ $merged, Node\Stmt\Class_ $left, Node\Stmt\Class_ $right)
    {
        if ($left->extends !== null && $right->extends !== null) {
            $merged->extend($this->chooseName($left, $right));
            return;
        }

        if ($this->preference === self::PREFER_LEFT) {
            if ($left->extends !== null) {
                $merged->extend($this->normalizeName($left->extends));
            }
            return;
        }

        if ($this->preference === self::PREFER_RIGHT) {
            if ($right->extends !== null) {
                $merged->extend($this->normalizeName($right->extends));
            }
            return;
        }

        throw new \RuntimeException('Could not choose between both versions, as PREFER_NONE mode is active.');
    }
}
