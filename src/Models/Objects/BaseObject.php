<?php

namespace Naran\Board\Models\Objects;

use Naran\Board\Interfaces\ObjectInterface;

use function Naran\Board\Functions\toPascalCase;
use function Naran\Board\Functions\toSnakeCase;

abstract class BaseObject implements ObjectInterface
{
    /**
     * @param array $array
     *
     * @return static
     */
    public static function fromArray($array)
    {
        $instance = static::getDefault();

        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $setterMethod = 'set' . toPascalCase($key);
                if (method_exists($instance, $setterMethod)) {
                    $instance->{$setterMethod}($value);
                }
            }
        }

        return $instance;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $output = [];

        try {
            foreach ((new \ReflectionClass($this))->getProperties(\ReflectionProperty::IS_PRIVATE) as $field) {
                $varName      = toSnakeCase($field->getName());
                $getterMethod = 'get' . toPascalCase($field->getName());
                if (method_exists($this, $getterMethod)) {
                    $output[$varName] = $this->{$getterMethod}();
                }
            }
        } catch (\ReflectionException $e) {
        }

        return $output;
    }
}
