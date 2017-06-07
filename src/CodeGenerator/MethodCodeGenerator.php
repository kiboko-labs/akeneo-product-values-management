<?php

namespace Kiboko\Component\AkeneoProductValues\CodeGenerator;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\AnnotationGeneratorInterface;
use Kiboko\Component\AkeneoProductValues\CodeContext\ArgumentContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\ClassReferenceContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\DefaultValueContextInterface;
use Kiboko\Component\AkeneoProductValues\CodeContext\ReturnContext;
use Kiboko\Component\AkeneoProductValues\Helper\ClassName;
use PhpParser\Builder;
use PhpParser\BuilderFactory;
use PhpParser\Node;

class MethodCodeGenerator implements Builder
{
    const ACCESS_PUBLIC = 'public';
    const ACCESS_PROTECTED = 'protected';
    const ACCESS_PRIVATE = 'private';

    /**
     * @var ClassCodeGeneratorInterface
     */
    private $parentGenerator;

    /**
     * @var string
     */
    private $access;

    /**
     * @var string
     */
    private $methodName;

    /**
     * @var ArgumentContext[]
     */
    private $arguments;

    /**
     * @var ReturnContext
     */
    private $returnType;

    /**
     * @var bool
     */
    private $isNullable;

    /**
     * @var bool
     */
    private $isStatic;

    /**
     * @var bool
     */
    private $isFinal;

    /**
     * @var AnnotationGeneratorInterface[]
     */
    private $annotationGenerators;

    /**
     * @var bool
     */
    private $isAbstract;

    /**
     * PropertyCodeGenerator constructor.
     *
     * @param ClassCodeGeneratorInterface $parentGenerator
     * @param string $methodName
     * @param AnnotationGeneratorInterface[] $annotationGenerators
     * @param bool $isAbstract
     */
    public function __construct(
        ClassCodeGeneratorInterface $parentGenerator,
        string $methodName,
        array $annotationGenerators = [],
        bool $isAbstract = false
    ) {
        $this->parentGenerator = $parentGenerator;
        $this->methodName = $methodName;
        $this->access = self::ACCESS_PUBLIC;
        $this->annotationGenerators = $annotationGenerators;
        $this->arguments = [];
        $this->isAbstract = $isAbstract;
        $this->isNullable = false;
        $this->isStatic = false;
        $this->isFinal = false;
    }

    /**
     * @return string
     */
    public function getAccess(): string
    {
        return $this->access;
    }

    /**
     * @param string $access
     */
    public function setAccess(string $access)
    {
        if (!in_array($access, [self::ACCESS_PUBLIC, self::ACCESS_PROTECTED, self::ACCESS_PRIVATE])) {
            throw new \RuntimeException(sprintf('Invalid access type: %s. Should be in [%s]',
                $access, implode(',', [self::ACCESS_PUBLIC, self::ACCESS_PROTECTED, self::ACCESS_PRIVATE])
            ));
        }

        $this->access = $access;
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }

    /**
     * @param string $methodName
     */
    public function setMethodName(string $methodName)
    {
        $this->methodName = $methodName;
    }

    /**
     * @return ReturnContext
     */
    public function getReturnType(): ReturnContext
    {
        return $this->returnType;
    }

    /**
     * @param ReturnContext $returnType
     */
    public function setReturnType(ReturnContext $returnType)
    {
        $this->returnType = $returnType;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->isNullable;
    }

    /**
     * @param bool $isNullable
     */
    public function setNullable(bool $isNullable)
    {
        $this->isNullable = $isNullable;
    }

    /**
     * @return bool
     */
    public function isAbstract(): bool
    {
        return $this->isAbstract;
    }

    /**
     * @param bool $isAbstract
     */
    public function setAbstract(bool $isAbstract)
    {
        $this->isAbstract = $isAbstract;
    }

    /**
     * @return bool
     */
    public function isStatic(): bool
    {
        return $this->isStatic;
    }

    /**
     * @param bool $isStatic
     */
    public function setStatic(bool $isStatic)
    {
        $this->isStatic = $isStatic;
    }

    /**
     * @return bool
     */
    public function isFinal(): bool
    {
        return $this->isFinal;
    }

    /**
     * @param bool $isFinal
     */
    public function setFinal(bool $isFinal)
    {
        $this->isFinal = $isFinal;
    }

    /**
     * @return ArgumentContext[]
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param ArgumentContext[] $arguments
     */
    public function setArguments(array $arguments)
    {
        foreach ($arguments as $argument) {
            $this->addArgument($argument);
        }
    }

    /**
     * @param ArgumentContext $argument
     */
    public function addArgument(
        ArgumentContext $argument
    ) {
        $this->arguments[] = $argument;
    }

    /**
     * @param string $argumentName
     * @param ClassReferenceContext $argumentType
     * @param DefaultValueContextInterface $default
     * @param bool $isNullable
     */
    public function buildArgument(
        string $argumentName,
        ClassReferenceContext $argumentType,
        DefaultValueContextInterface $default,
        bool $isNullable
    ) {
        $this->addArgument(
            new ArgumentContext($argumentName, $argumentType, $default, $isNullable)
        );
    }

    /**
     * @return Node
     */
    public function getNode()
    {
        $factory = new BuilderFactory();

        $root = $factory->method($this->methodName)
            ->setDocComment($this->compileDocComment());

        switch ($this->access) {
            case self::ACCESS_PUBLIC:
                $root->makePublic();
                break;

            case self::ACCESS_PROTECTED:
                $root->makePublic();
                break;

            case self::ACCESS_PRIVATE:
                $root->makePublic();
                break;
        }

        if ($this->isStatic) {
            $root->makeStatic();
        }

        if ($this->isFinal()) {
            $root->makeFinal();
        }

        if ($this->isAbstract) {
            $root->makeAbstract();
        }

        foreach ($this->arguments as $argument) {
            $root->addParam(
                $factory->param($argument->getName())
                    ->setTypeHint(
                        $argument->isNullable() ?
                            new Node\NullableType(
                                $argument->getType()->isScalar() ?
                                    ($argument->getType()->isAliased() ?: $argument->getType()->getClassName()) :
                                    new Node\Name($argument->getType()->isAliased() ?: $argument->getType()->getClassName())
                            ) :
                            $argument->getType()->isScalar() ?
                                ($argument->getType()->isAliased() ?: $argument->getType()->getClassName()) :
                                new Node\Name($argument->getType()->isAliased() ?: $argument->getType()->getClassName())
                    )
            );
        }

        return $root->getNode();
    }

    /**
     * @return string
     */
    protected function compileDocComment()
    {
        $return = '/**' . PHP_EOL;

        /** @var ArgumentContext $argument */
        foreach ($this->arguments as $argument) {
            $return .= '     * @param '.ClassName::formatDocTypeHintFromArgument($argument) .' $'. $argument->getName() . PHP_EOL;
        }

        if (count($this->arguments) > 0 && $this->returnType->getType()->getClassName() !== 'void') {
            $return .= '    *' . PHP_EOL;
        }

        if ($this->returnType->getType()->getClassName() !== 'void') {
            $return .= '    * @return ' . ClassName::formatDocTypeHint($this->returnType->getType(), $this->returnType->isNullable(), $this->returnType->isArray()) . PHP_EOL;
        }

        $return .= '     */';

        return $return;
    }
}
