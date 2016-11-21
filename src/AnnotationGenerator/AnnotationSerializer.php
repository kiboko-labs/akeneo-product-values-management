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
        $linePrefix = ' * ' . $pad;

        if ($generator instanceof CompositeAnnotationGeneratorInterface) {
            if ($generator->countChildren() <= 0) {
                return $linePrefix.'@'.$generator->getAnnotationClass().'()';
            }

            $serialized = $linePrefix.'@'.$generator->getAnnotationClass().'({';
            $count = 0;
            $maxCount = $generator->countChildren();
            foreach ($generator->getChildren() as $child) {
                $serialized .= $this->serialize($child, $depth + 1).(++$count < $maxCount ? ',' : '').PHP_EOL;
            }
            return $serialized.PHP_EOL.$linePrefix.'})';
        }

        if ($generator instanceof ParameteredAnnotationGeneratorInterface) {
            if ($generator->countParams() <= 0) {
                return $linePrefix.'@'.$generator->getAnnotationClass().'()';
            }

            return $linePrefix.'@'.$generator->getAnnotationClass().'('.PHP_EOL
                .$this->serializeParams(iterator_to_array($generator->getParams()), $depth + 1).PHP_EOL
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
        $linePrefix = ' * '.$pad;
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
                $serialized .= $linePrefix.$this->serializeArray($field, $value, $depth).(++$count < $maxCount ? ',' : '').PHP_EOL;
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
        $linePrefix = ' * '.$pad;
        $count = 0;
        $maxCount = count($values);
        $serialized = (is_numeric($field) ? '' : sprintf('%s={', $field).PHP_EOL);
        foreach ($values as $current) {
            $serialized .= $this->serialize($current, $depth + 1).(++$count < $maxCount ? ',' : '').PHP_EOL;
        }
        return $serialized.PHP_EOL.$linePrefix.'}';
    }
}
