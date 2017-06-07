<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use PhpParser\Node;

class StringConstantCodeGenerator implements ConstantCodeGeneratorInterface
{
    /**
     * @var ClassCodeGeneratorInterface
     */
    private $parentGenerator;

    /**
     * @var string
     */
    private $constantName;

    /**
     * @var string
     */
    private $constantValue;

    /**
     * PropertyCodeGenerator constructor.
     *
     * @param ClassCodeGeneratorInterface $parentGenerator
     * @param string $constantName
     * @param string $constantValue
     */
    public function __construct(
        ClassCodeGeneratorInterface $parentGenerator,
        string $constantName,
        string $constantValue
    ) {
        $this->parentGenerator = $parentGenerator;
        $this->constantName = $constantName;
        $this->constantValue = $constantValue;
    }

    /**
     * @return Node
     */
    public function getNode()
    {
        return new Node\Stmt\Const_(
            [
                $this->constantName => new Node\Scalar\String_($this->constantValue),
            ]
        );
    }
}
