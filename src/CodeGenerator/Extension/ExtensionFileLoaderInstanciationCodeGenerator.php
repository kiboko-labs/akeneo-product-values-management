<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator\Extension;

use PhpParser\Builder;
use PhpParser\Node;

class ExtensionFileLoaderInstanciationCodeGenerator implements Builder
{
    /**
     * @var string
     */
    private $variableName;

    /**
     * @var string
     */
    private $containerVariableName;

    /**
     * ExtensionYamlFileLoadingCodeGenerator constructor.
     * @param string $variableName
     * @param string $containerVariableName
     */
    public function __construct($variableName, $containerVariableName = 'container')
    {
        $this->variableName = $variableName;
        $this->containerVariableName = $containerVariableName;
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
    public function getContainerVariableName()
    {
        return $this->containerVariableName;
    }

    /**
     * @param string $containerVariableName
     */
    public function setContainerVariableName($containerVariableName)
    {
        $this->containerVariableName = $containerVariableName;
    }

    /**
     * @return Node
     */
    public function getNode()
    {
        return
            new Node\Expr\Assign(
                new Node\Expr\Variable($this->variableName),
                new Node\Expr\New_(
                    new Node\Name('Loader\\YamlFileLoader'),
                    [
                        new Node\Expr\Variable($this->containerVariableName),
                        new Node\Expr\New_(
                            new Node\Name('FileLocator'),
                            [
                                new Node\Expr\BinaryOp\Concat(
                                    new Node\Scalar\MagicConst\Dir(),
                                    new Node\Scalar\String_('/../Resources/config')
                                )
                            ]
                        )
                    ]
                )
            );
    }
}
