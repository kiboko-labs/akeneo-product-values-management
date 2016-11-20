<?php

namespace Kiboko\Component\AkeneoProductValues\AnnotationGenerator;

class AnnotationSerializer
{
    /**
     * @param AnnotationGeneratorInterface $generator
     * @param int $depth
     * @return string
     */
    public function serialize(AnnotationGeneratorInterface $generator, $depth = 0)
    {
        $pad = str_pad('', $depth * 4, ' ');
        $linePrefix = '     * ' . $pad;

        if ($generator instanceof CompositeAnnotationGeneratorInterface) {
            return $linePrefix.'@'.$generator->getAnnotationClass();
        }

        if ($generator instanceof ParameteredAnnotationGeneratorInterface) {
            if ($generator->countParams() <= 0) {
                return $linePrefix.'@'.$generator->getAnnotationClass().'()';
            }

            return $linePrefix.'@'.$generator->getAnnotationClass().'('.PHP_EOL
                .$this->serializeParams($generator->getParams(), $depth + 1).PHP_EOL
            .$linePrefix.')'.PHP_EOL;
        }

        return $linePrefix.'@'.$generator->getAnnotationClass();
    }

    /**
     * @param array $params
     * @param $depth
     * @return string
     */
    private function serializeParams(array $params, $depth)
    {
        $pad = str_pad('', $depth * 4, ' ');
        $linePrefix = '     * '.$pad;
        $maxCount = count($params);

        $serialized = '';
        $count = 0;
        foreach ($params as $field => $value) {
            if (is_bool($value)) {
                $serialized .= $linePrefix.$this->serializeBoolean($field, $value).(++$count < $maxCount ? ',' : '').PHP_EOL;
                continue;
            }
            if (is_string($value)) {
                $serialized .= $linePrefix.$this->serializeString($field, $value).(++$count < $maxCount ? ',' : '').PHP_EOL;
                continue;
            }
            if (is_float($value)) {
                $serialized .= $linePrefix.$this->serializeFloat($field, $value).(++$count < $maxCount ? ',' : '').PHP_EOL;
                continue;
            }
            if (is_integer($value)) {
                $serialized .= $linePrefix.$this->serializeInteger($field, $value).(++$count < $maxCount ? ',' : '').PHP_EOL;
                continue;
            }
            if (is_array($value)) {
                $serialized .= $this->serializeArray($field, $value, $depth + 1).(++$count < $maxCount ? ',' : '').PHP_EOL;
                continue;
            }
        }
        return $serialized;
    }

    /**
     * @param string $field
     * @param bool $value
     * @return string
     */
    private function serializeBoolean($field, $value)
    {
        return (is_numeric($field) ? '' : sprintf('%s=', $field)) . ($value ? 'true' : 'false');
    }

    /**
     * @param string $field
     * @param string $value
     * @return string
     */
    private function serializeString($field, $value)
    {
        return (is_numeric($field) ? '' : sprintf('%s=', $field)) . sprintf('"%s"', $value);
    }

    /**
     * @param string $field
     * @param float $value
     * @return string
     */
    private function serializeFloat($field, $value)
    {
        return (is_numeric($field) ? '' : sprintf('%s=', $field)) . sprintf('%f', $value);
    }

    /**
     * @param string $field
     * @param int $value
     * @return string
     */
    private function serializeInteger($field, $value)
    {
        return (is_numeric($field) ? '' : sprintf('%s=', $field)) . sprintf('%d', $value);
    }

    /**
     * @param string $field
     * @param array $values
     * @param int $depth
     * @return string
     */
    private function serializeArray($field, array $values, $depth)
    {
        $pad = str_pad('', $depth * 4, ' ');
        $linePrefix = '     * '.$pad;
        return (is_numeric($field) ? '' : sprintf('%s={', $field).PHP_EOL)
            .$this->serialize($values, $depth + 2)
            .PHP_EOL.$linePrefix.'}';
    }
}
