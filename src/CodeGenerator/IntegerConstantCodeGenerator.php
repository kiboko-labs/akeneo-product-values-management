<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use PhpParser\Node;

class IntegerConstantCodeGenerator implements ConstantCodeGeneratorInterface
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
     * @var integer
     */
    private $constantValue;

    /**
     * PropertyCodeGenerator constructor.
     *
     * @param ClassCodeGeneratorInterface $parentGenerator
     * @param string $constantName
     * @param int $constantValue
     */
    public function __construct(
        ClassCodeGeneratorInterface $parentGenerator,
        string $constantName,
        int $constantValue
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
                $this->constantName => new Node\Scalar\LNumber($this->constantValue),
            ]
        );
    }
}
