<?php

namespace Recca0120\RBAC;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Morph
{
    /**
     * morph users.
     *
     * @var array
     */
    public static $classes = [];

    /**
     * add define morphes.
     *
     * @param string $className
     */
    public static function push($className)
    {
        $key = Str::plural(Str::camel(class_basename($className)));
        self::$classes[$key] = $className;
    }

    public static function get($key)
    {
        return Arr::get(self::$classes, $key);
    }
}
