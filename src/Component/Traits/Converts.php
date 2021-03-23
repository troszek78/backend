<?php

namespace App\Component\Traits;

trait Converts
{
    /**
     * Class Name to Snake
     * @param string $fullClassName
     * @return string
     */
    public static function classNameToSnake(string $fullClassName): string
    {
        $snakeName = '';

        if ($fullClassName) {
            $position = strrpos($fullClassName, '\\');
            if ($position !== false) {
                $fullClassName = (substr($fullClassName, ++$position));
            }
            $snakeName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $fullClassName));
        }

        return $snakeName;
    }

    /**
     * @param string $snakeName
     * @return string
     */
    public static function snakeToLabel(string $snakeName): string
    {
        $label = str_replace('_', ' ', $snakeName);

        $label = ucwords($label);

        return $label;
    }
}
