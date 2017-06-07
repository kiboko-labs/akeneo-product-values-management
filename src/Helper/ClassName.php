<?php

namespace Kiboko\Component\AkeneoProductValues\Helper;

use Kiboko\Component\AkeneoProductValues\CodeContext\ArgumentContext;
use Kiboko\Component\AkeneoProductValues\CodeContext\ClassReferenceContext;
use PhpParser\Node;

class ClassName
{
    const SCALAR_TYPES = [
        'array',
        'bool',
        'boolean',
        'callable',
        'decimal',
        'float',
        'int',
        'integer',
        'mixed',
        'numeric',
        'null',
        'object',
        'string',
        'traversable',
    ];

    const ALIASED_TYPES = [
        'wysiwyg' => 'string',
        'dimension' => 'float',
        'asset' => 'Akeneo\\Component\\FileStorage\\Model\\FileInfoInterface',
    ];

    /**
     * @param ClassReferenceContext $class
     *
     * @return Node\Name
     */
    public static function buildNameNode(ClassReferenceContext $class): Node\Name
    {
        return $class->getAlias() ?
            new Node\Name\Relative($class->getAlias()) :
            new Node\Name\FullyQualified($class->getClassName());
    }

    /**
     * @param ClassReferenceContext $class
     * @param bool $isNullable
     * @param bool $isArray
     *
     * @return string
     */
    public static function formatTypeHint(
        ClassReferenceContext $class,
        bool $isNullable = false,
        bool $isArray = false
    ): string {
        if ($isArray) {
            return ($isNullable ? '?' : '') . 'array';
        }

        return ($isNullable ? '?' : '')
            . ($class->getAlias() ?: static::extractClass($class->getClassName()));
    }

    /**
     * @param ArgumentContext $argument
     *
     * @return string
     */
    public static function formatTypeHintFromArgument(ArgumentContext $argument): string
    {
        return self::formatTypeHint($argument->getType(), $argument->isNullable(), $argument->isArray());
    }

    /**
     * @param ClassReferenceContext $class
     * @param bool $isNullable
     * @param bool $isArray
     *
     * @return string
     */
    public static function formatDocTypeHint(
        ClassReferenceContext $class,
        bool $isNullable = false,
        bool $isArray = false
    ): string {
        return ($class->getAlias() ?: ((!$class->isScalar() ? '\\' : '') . static::extractClass($class->getClassName())))
            . ($isArray ? '[]' : '')
            . ($isNullable ? '|null' : '');
    }

    /**
     * @param ArgumentContext $argument
     *
     * @return string
     */
    public static function formatDocTypeHintFromArgument(ArgumentContext $argument): string
    {
        return self::formatDocTypeHint($argument->getType(), $argument->isNullable(), $argument->isArray());
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    public static function isScalar(string $className): bool
    {
        return in_array($className, self::SCALAR_TYPES);
    }

    /**
     * @param string $className
     *
     * @return string|null
     */
    public static function isAliased(string $className): ?string
    {
        return self::ALIASED_TYPES[$className] ?? null;
    }

    /**
     * @param string $className
     *
     * @return array
     */
    public static function extractClassAndNamespace($className): array
    {
        if (static::isScalar($className)) {
            return [$className, null];
        }

        $offset = static::findClassSeparator($className);

        return [
            static::extractClassFromOffset($className, $offset),
            static::extractNamespaceFromOffset($className, $offset),
        ];
    }

    /**
     * @param string $className
     *
     * @return bool|int
     */
    private static function findClassSeparator($className)
    {
        return strrpos($className, '\\');
    }

    /**
     * @param string $className
     * @param int $offset
     *
     * @return string
     */
    private static function extractClassFromOffset(string $className, int $offset): string
    {
        return substr($className, $offset + 1);
    }

    /**
     * @param string $className
     * @param int $offset
     *
     * @return string|null
     */
    private static function extractNamespaceFromOffset(string $className, int $offset): ?string
    {
        if ($offset === false) {
            return null;
        }

        return substr($className, 0, $offset);
    }

    /**
     * @param string $className
     *
     * @return string
     */
    public static function extractClass(string $className): string
    {
        if (static::isScalar($className)) {
            return $className;
        }

        if (($alias = static::isAliased($className)) !== null) {
            return $alias;
        }

        if (($offset =  static::findClassSeparator($className)) === false) {
            return $className;
        }

        return static::extractClassFromOffset($className, $offset);
    }

    /**
     * @param string $className
     *
     * @return string|null
     */
    public static function extractNamespace(string $className): ?string
    {
        if (static::isScalar($className)) {
            return null;
        }

        if (($offset =  static::findClassSeparator($className)) === false) {
            return null;
        }

        return static::extractNamespaceFromOffset($className, $offset);
    }
    /**
     * @param array $psr4Config
     * @param string $className
     *
     * @return string
     */
    public static function calculateFilePath(array $psr4Config, string $className)
    {
        foreach ($psr4Config as $namespace => $path) {
            if (strpos($className, $namespace) !== 0) {
                continue;
            }

            return $path  . '/' . str_replace('\\', DIRECTORY_SEPARATOR, substr($className, strlen($namespace))) . '.php';
        }

        return str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
    }
}
