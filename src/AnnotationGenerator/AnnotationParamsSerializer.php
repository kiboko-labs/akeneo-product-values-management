<?php


namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

class AnnotationParamsSerializer
{
    public function serialize(array $params)
    {
        foreach ($params as $field => $value) {
            if (is_string($value)) {
                yield (is_numeric($field) ? '' : sprintf('%s=', $field)) . sprintf('"%s"', $value);
                continue;
            }
            if (is_numeric($value)) {
                yield (is_numeric($field) ? '' : sprintf('%s=', $field)) . sprintf('%d', $value);
                continue;
            }
            if (is_array($value)) {
                yield (is_numeric($field) ? '' : sprintf('%s=', $field)) . sprintf('{ %s }', implode(', ',
                        iterator_to_array($this->serialize($value))));
                continue;
            }
            if ($value instanceof AnnotationGeneratorListInterface) {
                yield (is_numeric($field) ? '' : sprintf('%s=', $field)) . sprintf('%s', $value->getAnnotation());
                continue;
            }

            throw new \UnexpectedValueException(sprintf(
                'Unexpected annotation %s value, expecting array, numeric or string, but got %s',
                $field,
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }
    }
}
