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
            new Node\Name\FullyQualified($class->getName());
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
            . ($class->getAlias() ?: static::extractClass($class->getName()));
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
        return ($class->getAlias() ?: ((!$class->isScalar() ? static::extractClass($class->getName()) : $class->getName())))
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
     * @param string $name
     *
     * @return bool
     */
    public static function isScalar(string $name): bool
    {
        return in_array($name, self::SCALAR_TYPES);
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public static function isAliased(string $name): ?string
    {
        return self::ALIASED_TYPES[$name] ?? null;
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public static function extractClassAndNamespace($name): array
    {
        if (static::isScalar($name)) {
            return [$name, null];
        }

        $offset = static::findClassSeparator($name);

        return [
            static::extractClassFromOffset($name, $offset),
            static::extractNamespaceFromOffset($name, $offset),
        ];
    }

    /**
     * @param string $name
     *
     * @return bool|int
     */
    private static function findClassSeparator($name)
    {
        return strrpos($name, '\\');
    }

    /**
     * @param string $name
     * @param int $offset
     *
     * @return string
     */
    private static function extractClassFromOffset(string $name, int $offset): string
    {
        return substr($name, $offset + 1);
    }

    /**
     * @param string $name
     * @param int $offset
     *
     * @return string|null
     */
    private static function extractNamespaceFromOffset(string $name, int $offset): ?string
    {
        if ($offset === false) {
            return null;
        }

        return substr($name, 0, $offset);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public static function extractClass(string $name): string
    {
        if (static::isScalar($name)) {
            return $name;
        }

        if (($alias = static::isAliased($name)) !== null) {
            return $alias;
        }

        if (($offset = static::findClassSeparator($name)) === false) {
            return $name;
        }

        return static::extractClassFromOffset($name, $offset);
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public static function extractNamespace(string $name): ?string
    {
        if (static::isScalar($name)) {
            return null;
        }

        if (($offset =  static::findClassSeparator($name)) === false) {
            return null;
        }

        return static::extractNamespaceFromOffset($name, $offset);
    }
    /**
     * @param array $psr4Config
     * @param string $name
     *
     * @return string
     */
    public static function calculateFilePath(array $psr4Config, string $name)
    {
        foreach ($psr4Config as $namespace => $path) {
            if (strpos($name, $namespace) !== 0) {
                continue;
            }

            return $path  . '/' . str_replace('\\', DIRECTORY_SEPARATOR, substr($name, strlen($namespace))) . '.php';
        }

        return str_replace('\\', DIRECTORY_SEPARATOR, $name) . '.php';
    }
}
