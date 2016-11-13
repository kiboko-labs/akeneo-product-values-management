<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator\Extension;

use PhpParser\Builder;
use PhpParser\Node;

class ExtensionYamlFileLoadingCodeGenerator implements Builder
{
    /**
     * @var string
     */
    private $variableName;

    /**
     * @var string
     */
    private $filename;

    /**
     * ExtensionYamlFileLoadingCodeGenerator constructor.
     * @param string $variableName
     */
    public function __construct($variableName, $filename)
    {
        $this->variableName = $variableName;
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function getVariableName()
    {
        return $this->variableName;
    }

    /**
     * @param string $variableName
     */
    public function setVariableName($variableName)
    {
        $this->variableName = $variableName;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return Node
     */
    public function getNode()
    {
        return new Node\Expr\MethodCall(
            new Node\Expr\Variable($this->variableName),
            'load',
            [
                new Node\Scalar\String_($this->filename)
            ]
        );
    }
}
